<?php

namespace App\Services;

use App\Models\Appraisal\OrderFile;
use App\Models\Appraisal\OrderAddenda;
use App\Models\Appraisal\Order;
use App\Models\Customizations\Addenda;
use App\Models\Documents\DocumentType;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Helpers\StringHelper;
use App\Models\Tools\Setting;
use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\Clients\Client;
use App\Models\Lenders\ExcludedProfiles;
use App\Models\Customizations\AMCLicense;
use App\Models\Appraisal\QC\DataAnswer;
use App\Models\Tiger\Amc;
use App\Models\Appraisal\ApprOrderRemotePendingSubmission;
use Carbon\Carbon;
use App\Models\Api\Subscriber;

class OrderFunctionsService
{
    protected static $shouldUpdateOrderPricing = true;

    /**
     * @param $orderId
     * @return mixed
     */
    public static function getOrderFinalAWSReportXML($orderId)
    {
        $order = Order::where('id', $orderId)->first();
        if ($order->revision) {
            $row = OrderFile::where('order_id', $order->id)->where('is_xml', 1)->where('revision', $order->revision)->first();
        } else {
            $row = OrderFile::where('order_id', $order->id)->where('is_xml', 1)->orderBy('id', 'asc')->first();
        }
        return $row;
    }

    /**
     * Mark Third Party Documents Visible Post Completion
     */
    public static function markThirdPartyDocumentsVisiblePostCompletion($order)
    {
        self::changeOrderFileVisiblity($order, 0);
        self::changeOrderFileVisiblity($order, 1);
    }

    /**
     * Change Order File Visiblity
     */
    private static function changeOrderFileVisiblity($order, $visiblity)
    {
        OrderFile::where('order_id', $order->id)
                    ->whereIn('document_type', [
                        self::getDocumentTypeIdByCode('REALVIEWPDFREPORTSUMMARY'),
                        self::getDocumentTypeIdByCode('REALVIEWPDFREPORT'),
                        self::getDocumentTypeIdByCode('UCDPFNMSSR'),
                        self::getDocumentTypeIdByCode('UCDPFRESSR'),
                        self::getDocumentTypeIdByCode('EADSSR')
                    ])->update(['is_client_visible' => $visiblity]);
    }

    /**
     * @param $id
     * @param $data
     * @return string
     */
    public static function saveAppraiserOrder($id, $data)
    {
        try {
            if ($id) {
                $old = Order::where('id', $id)->first();
                $data = self::getAdditionalUpdates($data, $old);
                Order::where('id', $id)->update($data);
                $isNew = false;
            } else {
                $old = [];
                Order::insert($data);
                $id = DB::getPdo()->lastInsertId();
                $isNew = true;
            }
            self::saveAppraiserOrderActivity($id, getUserOrSuper(), $old, $data);
            $order = Order::where('id', $id)->first();
            if ($order->api_user) {
                self::addApiPendingPostBack($order->api_user, $id, 'appraisal');
            }

            //TODO self::createSupportTicket($ticketInfo);

            if (isset($data['is_assigned'])) {
                self::borrowerVendorAssignedNotification($order);
                Order::where('id', $id)->update([
                    'unassigned_date' => time()
                ]);
            }
            if (isset($data['is_assigned']) && $order->is_client_approval) {
                Order::where('id', $id)->update([
                    'is_client_approval' => 0,
                    'client_approval_status' => 0,
                    'client_approval_reason' => ''
                ]);
            }
            if (!$isNew) {
                if ($order->is_mercury) {
                    //TODO Mercury Order
                }
                if ($order->is_valutrac) {
                    //TODO ValuTrac Order
                }
                if ($order->is_fnc) {
                    //TODO FNC Order
                }
                // If this is completed then see if we need to charge a fee
                if ($order->status == Order::STATUS_APPRAISAL_COMPLETED) {
                    //TODO Tiger ChargeFee
                }
            }
            //TODO self::chargeCard($order, $data);
            if (self::$shouldUpdateOrderPricing) {
                //TODO self::savePricingVersion($order);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return $id;
    }

    /**
     * @param $orderId
     * @param $userId
     * @param $current
     * @param $updates
     * @return string|void
     */
    public static function saveAppraiserOrderActivity($orderId, $userId, $current, $updates)
    {
        if ($updates) {
            return;
        }
        try {
            AppraisalOrderActivity::insert([
                'order_id' => $orderId,
                'user_id' => $userId,
                'created_date' => time()
            ]);
            $newRecordId = DB::getPdo()->lastInsertId();
            foreach ($updates as $key => $value) {
                $currentValue = isset($current->$key) ? $current->$key : null;
                if ($currentValue === $value) {
                    continue;
                }
                ApprOrderActivityRecord::insert([
                    'activity_id' => $newRecordId,
                    'column_name' => $key,
                    'from_value' => $currentValue,
                    'to_value' => $value
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return $newRecordId;
    }

    /**
     * @param $id
     * @param $relId
     * @param $type
     * @return string
     */
    public static function addApiPendingPostBack($id, $relId, $type)
    {
        $rows = Subscriber::select('api_subscriber.*', '', '')
            ->leftjoin('api_subscriber_type as t', 't.subscriber_id', '=', 'api_subscriber.id')
            ->leftJoin('api_subscriber_pending_post as p', function ($j) use ($relId) {
                $j->on('api_subscriber.id', '=', 'p.subscriber_id')->where('p.rel_id', $relId);
            })
            ->where('api_subscriber.api_id', $id)
            ->where('t.type', $type)
            ->where(\DB::raw('p.id IS NULL'))
            ->where('api_subscriber.subscribe_active', 1)
            ->get();

        if ($rows) {
            foreach ($rows as $row) {
                try {
                    ApiSubscriberPendingPost::insert([
                        'subscriber_id' => $row->id,
                        'rel_id' => $relId,
                        'created_date' => time()
                    ]);
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
    }

    /**
     * Borrower Vendor Assigned Notification
     * @return mixed
     */
    public static function borrowerVendorAssignedNotification($order)
    {
        if (!$order->borrower_email || !self::getAppraiserSettingContent('borrower_vendor_assigned_email_alert') || !self::getAppraiserSettingContent('borrower_vendor_assigned_email_alert_subject')) {
            return false;
        }

        $body = self::convertKeys(self::getAppraiserSettingContent('borrower_vendor_assigned_email_alert'), $order);
        $subjectLine = self::convertKeys(self::getAppraiserSettingContent('borrower_vendor_assigned_email_alert_subject'), $order);

        //TODO Client email
    }

    /**
     * Get Appraiser Setting Content
     * @return mixed
     */
    public static function getAppraiserSettingContent($key)
    {
        $settings = Setting::where('setting_key', $key)->orderBy('id', 'asc')->first();
        return self::getSettingsValue($settings);
    }

    /**
     * @param $settings
     * @return string
     */
    public static function getSettingsValue($settings)
    {
        return $settings->value !== "" ? $settings->value : $settings->default_value;
    }

    /**
     * Get Additional Updates
     * @return mixed
     */
    private static function getAdditionalUpdates($updates, $order)
    {
        if ((isset($updates['status']) && $updates['status'] == Order::STATUS_SCHEDULED) || isset($updates['schd_date'])) {
            if ($order->date_scheduled) {
                $updates['date_rescheduled'] = Carbon::now()->timestamp;
            } else {
                $updates['date_scheduled'] = Carbon::now()->timestamp;
            }
        }

        if ((isset($updates['status']) && $updates['status'] == Order::LOAN_TYPE_NA) && $order->borrower_contact) {
            $updates['borrower_contact'] = 0;
        }

        return $updates;
    }

    /**
     * Return team info based on group id
     * @return collection
     */
    private static function getGroupTeamByGroupId($groupId)
    {
        return AdminTeam::select('admin_teams.*', 'c.*')->leftJoin('admin_team_client as c', 'c.team_id', '=', 'admin_teams.id')->where('c.user_group_id', $groupId)->first();
    }

    /**
     * Get Document Signed Url
     * @return array
     */
    private static function getDocumentSignedUrl($type, $order)
    {
        $id = self::getDocumentTypeIdByCode($type);
        $row = null;
        if ($id) {
            $row = OrderFile::where('document_type', $id)->where('order_id', $order->id)->orderBy('id', 'DESC')->first();
        }
        if (!$row) {
            $row = OrderFile::where('id', $type)->where('order_id', $order->id)->orderBy('id', 'DESC')->first();
        }
        if (!$row) {
            return false;
        }
        $signedUrl = false;
        $name = self::getDocumentTypeNameById($id);
        if ($name == 'N/A') {
            $name = $row->docname;
        }
        return ['url' => $signedUrl, 'title' => $name];
    }

    /**
     * Get Document Type Id B yCode
     * @return int
     */
    private static function getDocumentTypeIdByCode($code)
    {
        if (!$code) {
            return 0;
        }
        $row = self::getDocumentTypeByCode($code);
        return $row ? $row->id : 0;
    }

    /**
     * Get Document Type By Code
     * @return collection
     */
    private static function getDocumentTypeByCode($code)
    {
        return DocumentType::where('code', $code)->first();
    }

    /**
     * Get Document Type Name By Id
     * @return string
     */
    private static function getDocumentTypeNameById($id)
    {
        if (!$id) {
            return 'N/A';
        }
        $row = self::getDocumentTypeById($id);
        return $row->name;
    }

    /**
     * Get Document Type By Id
     * @return collection
     */
    private static function getDocumentTypeById($id)
    {
        return DocumentType::where('id', $id)->first();
    }

    /**
     * Get Attribute Value
     * @return string|null
     */
    private static function getAttributeValue($attributes, $key, $cast = 'string')
    {
        foreach ($attributes as $k => $v) {
            if ($k == $key) {
                if ($cast == 'array') {
                    return (array) $v;
                } elseif ($cast == 'object') {
                    return (object) $v;
                } elseif ($cast == 'int') {
                    return (int) $v;
                } else {
                    return (string) $v;
                }
            }
        }
        return null;
    }

    /**
     * Get User Signature
     * @return string
     */
    public static function getUserSignature($id)
    {
        $user = userInfo($id, true);
        if (!$user) {
            return null;
        }
        return $user->email_signature;
    }

    /**
     * Has Third Party Submission
     * @return collection
     */
    public static function hasThirdPartySubmission($id, $type)
    {
        return ApprOrderRemotePendingSubmission::where('order_id', $id)->where('type', $type)->orderBy('id', 'DESC')->first();
    }
}
