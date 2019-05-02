<?php

namespace App\Models\Appraisal;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Users\User;
use App\Models\Tools\Setting;
use App\Models\Appraisal\UW\UW;
use App\Models\Management\ZipCode;
use App\Models\DocuVault\Notification;
use App\Models\Appraisal\QC\DataAnswer;
use App\Models\Accounting\AccountingPayablePaymentRecord;

class Order extends BaseModel
{
    const STATUS_TEMP = 9;
    const STATUS_APPRAISAL_COMPLETED = 6;
    const STATUS_CANCELLED = 10;
    const STATUS_SCHEDULED = 3;
    const STATUS_CANCELLED_TRIP_FEE = 20;
    const STATUS_UNASSIGNED = 8;
    const STATUS_UW_APPROVAL = 17;
    const STATUS_UW_CONDITION = 14;
    const STATUS_QC = 15;
    const STATUS_RECONSIDERATION = 18;
    const STATUS_PENDING_CORRECTIONS = 19;
    const STATUS_AWAITING_CLIENT_APPROVAL = 21;
    const STATUS_HOLD_UW_CONDITIONS = 14;
    const STATUS_HOLD_UW_APPROVAL = 17;


    const STATUS_COD = 'COD';
    const STATUS_COLLECT_FROM_BORROWER = 'Collect From Borrower';
    const STATUS_REFUND_DUE = 'Refund Due';
    const STATUS_REFUNDED = 'Refunded';
    const STATUS_PAID = 'Paid';
    const STATUS_INVOICED = 'Invoiced';
    const STATUS_BALANCE_DUE = 'Balance Due';
    const STATUS_UNPAID = 'Unpaid';
    const STATUS_FHA = 'FHA';
    const STATUS_USDA = 'USDA';
    const STATUS_CONVENTIONAL = 'Conventional';

    const LOAN_TYPE_CONVENTIONAL = 1;
    const LOAN_TYPE_FHA = 2;
    const LOAN_TYPE_USDA = 4;
    const LOAN_TYPE_NA = 5;
    const LOAN_TYPE_ASSET_VALUATION = 7;

    const APPR_TYPE_FULL = 1;
    const APPR_TYPE_CONDO = 4;
    const APPR_TYPE_MANF = 15;
    const APPR_TYPE_FULL_EPP = 71;
    const APPR_TYPE_CONDO_EPP = 72;
    const APPR_TYPE_MANF_EPP = 73;
    const APPR_TYPE_BUILD_TASK = 83;
    const APPR_TYPE_CONDO_BTF = 84;
    const APPR_TYPE_MANF_BTF = 86;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appr_order';

    protected $fillable = [
        'is_escalated_worked_today',
        'is_delayed',
        'is_delayed_complete',

        'unassigned_date',
        'is_client_approval',
        'client_approval_status',
        'client_approval_reason',
        'appr_paid',
        'groupid',

    ];

    protected $appends = ['address', 'shortapprtypename', 'paymentstatus'];

    public static function getApprOrderById($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'appraisal';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'fulladdress' => $this->address,
            'address' => $this->propaddress1,
            'address2' => $this->propaddress2,
            'city' => $this->propcity,
            'state' => $this->propstate,
            'zip' => $this->propzip,
            'id' => (string)$this->id,
            'loan' => $this->loanrefnum,
            'borrower' => $this->borrower,
            'appraisal_type' => $this->appraisalTypeName,
            'client' => $this->clientName,
            'loanrefnum' => $this->loanrefnum,
            'status' => $this->status,
            'statusName' => $this->statusName,
            'date_delivered' => $this->date_delivered,
            'date_ordered' => $this->ordereddate,
        ];

        return $array;
    }

    /**
     * @param string $template
     * @param int $orderId
     * @return string
     */
    public function convertKeys($message)
    {
        $message = $this->bindOrderParams($message);

        if (admin() && admin()->id && admin()->userData) {
            $message = str_replace('{signature}', admin()->userData->email_signature, $message);
        }

        // Make sure we do not replace {html}
        $message = str_replace('{html}', '%html%', $message);
        $message = str_replace('{corrections}', '%corrections%', $message);

        // Remove any {(.*)} left
        $message = preg_replace('/\{(.*)\}/', '', $message);

        // Bring back {html}
        $message = str_replace('%html%', '{html}', $message);
        $message = str_replace('%corrections%', '{corrections}', $message);
        return $message;
    }

    /**
     * @param string $message
     * @param \App\Models\Appraisal\Order $order
     * @return string
     */
    protected function bindOrderParams($message)
    {
        // Convert file downloads
        if (strpos($message, '{download_file=') !== false) {
            // TODO: LM-2 convertDownloadFiles
            // $message = static::convertDownloadFiles($message, $order);
        }

        // We load everything
        $orderKeys = [
            '{apprtype}' => str_contains($message, '{apprtype}') ? $this->apprTypeName : null,
            '{loantype}' => str_contains($message, '{loantype}') ? $this->loanTypeTitle : null,
            '{loanreason}' => str_contains($message, '{loanreason}') ? $this->loanReasonName : null,
            '{due_date}' => $this->due_date ? date('m/d/Y', $this->due_date) : '',
            '{client_due_date}' => $this->client_due_date ? date('m/d/Y', $this->client_due_date) : '',
            '{FULL_URL}' => config('app.url'),
            '{FULLURL}' => config('app.url'),
            '{URL}' => config('app.url'),
            '{date}' => date('m/d/Y'),
            '{datetime}' => date('m/d/Y g:i A'),
            '{apiname}' => str_contains($message, '{apiname}') ? $this->apiName : null,
            '{paymentstatus}' => $this->paymentStatus,
            '{status.name}' => str_contains($message, '{status}') ? $this->statusName : null,
            '{addendas}' => str_contains($message, '{addendas}') ? $this->addendasList : null,
            '{phone}' => str_contains($message, '{phone}') ? $this->teamPhone : null,
            '{fulladdress}' => $this->address,
            '{date_delivered}' => date('m/d/Y H:i', strtotime($this->date_delivered)),
            '{fha_case_effective_date}' => $this->fha_case_effective_date
                ? date('m/d/Y', strtotime($this->fha_case_effective_date))
                : '',
            '{real_lender}' => $this->real_lender ?: $this->lender,
            '{real_lender_address}' => $this->real_lender_address ?: '',
            '{real_lender_city}' => $this->real_lender_city ?: '',
            '{real_lender_state}' => $this->real_lender_state ?: '',
            '{real_lender_zip}' => $this->real_lender_zip ?: '',
            '{scheduled_appointments}' => str_contains($message, '{scheduled_appointments}') ? $this->appointmentScheduleMessage : null,
        ];

        foreach ($this->toArray() as $k => $v) {
            if (!isset($orderKeys['{' . $k . '}'])) {
                $orderKeys['{' . $k . '}'] = $v;
            }
        }

        // Convert user info
        if (str_contains($message, '{user.') && $this->client) {
            foreach ($this->client->toArray() as $k => $v) {
                $orderKeys['{user.' . $k . '}'] = $v;
            }

            if($this->client->userData) {
              foreach ($this->client->userData->toArray() as $k => $v) {
                  $orderKeys['{user.' . $k . '}'] = $v;
              }
            }
        }

        // Convert group info
        if (str_contains($message, '{group.') && $this->groupData) {
            foreach ($this->groupData->toArray() as $k => $v) {
                $orderKeys['{group.' . $k . '}'] = $v;
            }
        }

        // Convert lender info
        if (str_contains($message, '{lender.') && $this->lenderRecord) {
            foreach ($this->lenderRecord->toArray() as $k => $v) {
                $orderKeys['{lender.' . $k . '}'] = $v;
            }
        }

        // Convert amc info
        if (str_contains($message, '{amc.') && $this->registration) {
            foreach ($this->registration->toArray() as $k => $v) {
                $orderKeys['{amc.' . $k . '}'] = $v;
            }
        }

        // Convert settings
        if (str_contains($message, '{setting.')) {
            $settings = Setting::getSettings();
            if ($settings && count($settings)) {
                foreach ($settings as $k => $v) {
                    $orderKeys['{setting.' . $k . '}'] = $v;
                }
            }
        }

        // QC Data Collection
        if (str_contains($message, '{qc.data.') && $this->qcDataAnswers) {
            foreach ($this->qcDataAnswers as $row) {
                $orderKeys['{qc.data.' . $row->question_id . '}'] = $row->formatValue;
            }
        }

        if (str_contains($message, '{appr.') || str_contains($message, '{vendor.')) {
            if ($this->amc) { // AMC
                $default = [
                  'firstname' => trim($this->amc->title),
                  'lastname' => null,
                  'email' => $this->amc->outgoing_email,
              ];

                $collection = collect($default);
                $fields = $collection->union($this->amc);

                foreach ($fields as $key => $value) {
                    $orderKeys['{appr.' . $key . '}'] = $value;
                    $orderKeys['{vendor.' . $key . '}'] = $value;
                }
            } elseif ($this->acceptedby) { // Appraiser
                if ($this->userDataByAcceptedBy) {
                    foreach ($this->userDataByAcceptedBy->toArray() as $key => $value) {
                        $orderKeys['{appr.' . $key . '}'] = $value;
                        $orderKeys['{vendor.' . $key . '}'] = $value;
                    }
                }
            }
        }

        // We have everything loaded here
        return strtr($message, $orderKeys);
    }

    /**
     * @return string
     */
    public function getVendorNameAttribute()
    {
        if ($this->amc) { // AMC
            return trim($this->amc->title);
        }

        if ($this->acceptedby) { // Appraiser
            $user = userInfo($this->acceptedby, true);

            return $user->fullname;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getGroupNameAttribute()
    {
        return $this->groupData ? $this->groupData->descrip : null;
    }

    /**
     * Get Order Legend Data
     * @return mixed
     */
    public static function orderLegend()
    {
        $date = date('2014-01-01 00:00:00');
        return self::where('ordereddate', '>', $date)
        ->where('is_assigned', 1)
        ->where(function ($query) {
            $query->where('groupid', '>', 0)
                  ->orWhere('lender_id', '>', 0)
                  ->orWhere('lender_id', '=', -1);
        })->inRandomOrder()->first();
    }

    /**
     * @return string
     */
    public function getShortAddressAttribute()
    {
        return trim($this->propaddress1 . ', ' . $this->propcity);
    }

    /**
     * @return string|null
     */
    public function getClientNameAttribute()
    {
        return $this->client ? $this->client->fullname : null;
    }

    /**
     * @return string|null
     */
    public function getAppraisalTypeNameAttribute()
    {
        return $this->appraisalType ? $this->appraisalType->title : null;
    }

    /**
     * @return string|null
     */
    public function getStatusNameAttribute()
    {
        return $this->orderStatus ? $this->orderStatus->descrip : null;
    }

    /**
     * @return string
     */
    public function getOrderedByUserAttribute()
    {
        return $this->client ? $this->client->fullname : config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getApprTypeNameAttribute()
    {
        return $this->apprType
            ? $this->apprType->form . ' ' . $this->apprType->descrip
            : config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getShortApprTypeNameAttribute()
    {
        return $this->apprType
            ? $this->apprType->form . ' ' . $this->apprType->short_descrip
            : config('constants.not_available');
    }

    /**
     * @return string|null
     */
    public function getApprTypeShortNameAttribute()
    {
        return $this->appraisalType ? $this->appraisalType->form . ' ' . $this->appraisalType->short_descrip : null;
    }

    /**
     * @return string
     */
    public function getLoanTypeTitleAttribute()
    {
        if ($this->loantype == self::LOAN_TYPE_FHA || $this->req_fha == 'Y') {
            return self::STATUS_FHA;
        }

        if ($this->loantype == self::LOAN_TYPE_USDA) {
            return self::STATUS_USDA;
        }

        return self::STATUS_CONVENTIONAL;
    }

    /**
     * @return string
     */
    public function getLoanReasonNameAttribute()
    {
        return $this->loanReason ? $this->loanReason->descrip : config('constants.not_available');
    }

    /**
     * @return string|null
     */
    public function getApiNameAttribute()
    {
        return $this->apiUser ? $this->apiUser->title : null;
    }

    /**
     * @return false|string
     */
    public function getFormatOrderDateAttribute()
    {
        return date('m/d/y', strtotime($this->ordereddate));
    }

    /**
     * @return string
     */
    public function getPaymentStatusAttribute()
    {
        if ($this->is_cod == 'Y' && $this->billmelater != 'Y' && !$this->is_check_payment
            && $this->paid_amount <= $this->invoicedue) {
            return self::STATUS_COD;
        }

        if ($this->is_collect_from_borrower) {
            return self::STATUS_COLLECT_FROM_BORROWER;
        }

        if ($this->paid_amount > $this->invoicedue) {
            return self::STATUS_REFUND_DUE;
        }

        if ($this->refund_date) {
            return self::STATUS_REFUNDED;
        }

        if ($this->is_order_paid || ($this->paid_amount > 0 && $this->invoicedue
                && $this->paid_amount >= $this->invoicedue)) {
            return self::STATUS_PAID;
        }

        if ($this->billmelater == 'Y' || $this->is_check_payment) {
            return self::STATUS_INVOICED;
        }

        if ($this->paid_amount > 0 && $this->paid_amount < $this->invoicedue) {
            return self::STATUS_BALANCE_DUE;
        }

        return self::STATUS_UNPAID;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAddendas()
    {
        return OrderAddenda::where('order_id', $this->id)
            ->join('addendas', 'addendas.id', '=', 'appr_order_addenda.addenda')
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getPaymentHistory()
    {
        return ApprCimPayment::from('appr_cim_payments as pa')
            ->select(\DB::raw(
                "pa.*, pr.*, pa.id as payment_id,
                u.firstname, u.lastname, pr.card_name,
                pa.created_date as created_date"
            ))->leftJoin('user_data as u', 'u.user_id', 'pa.user_id')
            ->leftJoin('appr_cim_profile as pr', function ($join) {
                $join->on('pa.cim_profile_id', 'pr.cim_profile_id');
                $join->on('pa.cim_payment_profile_id', 'pr.cim_payment_profile_id');
            })->where('pa.order_id', $this->id)
            ->latest('pa.created_date')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getCheckPaymentHistory()
    {
        return CimCheckPayment::from('appr_cim_check_payments as pa')
            ->select(\DB::raw(
                "pa.*, u.firstname, u.lastname"
            ))->leftJoin('user_data as u', 'u.user_id', 'pa.user_id')
            ->where('pa.order_id', $this->id)
            ->latest('pa.created_date')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAdjustmentsPaymentHistory()
    {
        return ApprAccountingAdmin::from('appr_accounting_admin as pa')
            ->select(\DB::raw(
                "pa.*, u.firstname, u.lastname"
            ))->leftJoin('user_data as u', 'u.user_id', 'pa.created_userid')
            ->where('pa.order_id', $this->id)
            ->latest('created_date')->get();
    }

    /**
     * @return string
     */
    public function getAddendasListAttribute()
    {
        $rows = OrderAddenda::where('order_id', $this->id)
            ->join('addendas', 'addendas.id', '=', 'appr_order_addenda.addenda')
            ->get();

        $items = [];
        foreach ($rows as $row) {
            if ($row->descrip == 'other') {
                $items[$row->addenda] = $row->other;
            } else {
                $items[$row->addenda] = $row->descrip;
            }
        }

        if ($items) {
            return ' (' . implode(', ', $items) . ')';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getTeamPhoneAttribute()
    {
        return ($this->adminTeamClient && $this->adminTeamClient->adminTeam)
            ? $this->adminTeamClient->adminTeam->team_phone
            : null;
    }

    /**
     * @return string
     */
    public function getTeamTitle()
    {
        return ($this->adminTeamClient && $this->adminTeamClient->adminTeam)
            ? $this->adminTeamClient->adminTeam->team_title
            : null;
    }

    /**
     * @return mixed
     */
    public function getTeamIdAttribute()
    {
        return $this->adminTeamClient ? $this->adminTeamClient->team_id : null;
    }

    /**
     * @return string
     */
    public function getAddressAttribute()
    {
        return \App\Helpers\Address::getFullAddress(
            $this->propaddress1,
            $this->propaddress2,
            $this->propcity,
            $this->propstate,
            $this->propzip
        );
    }

    /**
     * @return string
     */
    public function getBalanceAttribute()
    {
        $result = $this->invoicedue - $this->paid_amount;
        return sprintf("%.2f", $result);
    }

    /**
     * @return string
     */
    public function getMarginAttribute()
    {
        $result = $this->invoicedue - $this->split_amount;
        return sprintf("%.2f", $result);
    }

    public function getPlacedDateAttribute()
    {
        return formatDate($this->ordereddate);
    }

    /**
     * @return mixed
     */
    public function getCompletedDateAttribute()
    {
        if ($this->date_delivered) {
            return $this->date_delivered;
        }
        if ($this->completed) {
            return $this->completed;
        }
        if ($this->submitted) {
            return $this->submitted;
        }
        return $this->ordereddate;
    }

    /**
     * @return string
     */
    public function getAppointmentScheduleMessageAttribute()
    {
        $message = '<ul>';

        if ($this->appointmentSchedule) {
            foreach ($this->appointmentSchedule as $row) {
                $message .= sprintf('<li>%s</li>', date('m/d/Y G:i A', $row->appointment_date));
            }
        } else {
            $message .= '<li>No Appointments Scheduled.</li>';
        }

        $message .= '</ul>';

        return $message;
    }

    /**
     * @return string
     */
    public function getSearchDateString()
    {
        return $this->date_delivered
            ? (Carbon::createFromFormat('Y-m-d H:i:s', $this->date_delivered)
                    ->format('m/d/Y') . ' Delivered')
            : (Carbon::createFromFormat('Y-m-d H:i:s', $this->ordereddate)
                    ->format('m/d/Y') . ' Ordered');
    }

    // --------------------

    /**
     * @param $query
     * @param bool|array $states
     * @return mixed
     */
    public function scopeOfStates($query, $states = false)
    {
        if ($states !== false) {
            return $query->whereIn('propstate', $states);
        }
    }

    /**
     * @param $query
     * @param bool|string $borrower
     * @return mixed
     */
    public function scopeOfBorrower($query, $borrower = false)
    {
        if ($borrower !== false) {
            return $query->where('borrower', 'like', '%' . $borrower . '%');
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        $query->where('status', '!=', static::STATUS_TEMP);
    }

    public function scopeOfStatusFilter($query, $type = null)
    {
        $statuses = explode(',', setting('appr_order_ignore_statuses'));
        if ($type == 'active') {
            $query->whereNotIn($this->qualifyColumn('status'), array_unique(array_merge($statuses, [static::STATUS_APPRAISAL_COMPLETED, static::STATUS_CANCELLED_TRIP_FEE])));
        } elseif ($type == 'completed') {
            $query->whereIn($this->qualifyColumn('status'), [
          static::STATUS_APPRAISAL_COMPLETED,
          static::STATUS_UW_CONDITION,
          static::STATUS_UW_APPROVAL,
          static::STATUS_RECONSIDERATION
        ]);
        } else {
            $query->whereNotIn($this->qualifyColumn('status'), $statuses);
        }
    }

    public function scopeOfPlacedBy($query, $id)
    {
        $query->where($this->qualifyColumn('orderedby'), $id);
    }

    public function scopeOfAssignedTo($query, $id)
    {
        $query->where($this->qualifyColumn('acceptedby'), $id);
    }

    public function scopeIsAssigned($query)
    {
      $query->where($this->qualifyColumn('is_assigned'), 1);
    }

    public function relatedGroupIds($id)
    {
        $user = (new User)->with(['groups'])->find($id);
        $ids = [];

        if ($user && $user->groups) {
            $ids = $user->groups->pluck('group_id');
        }

        return $ids;
    }

    public function usersInRelatedGroups($id)
    {
        $user = (new User)->with(['groups', 'group', 'group.activeUsers'])->find($id);
        $ids = [];

        if ($user) {
            if ($user->groups) {
                $ids = array_merge($ids, $user->groups->pluck('user_id')->all());
            }

            if ($user->group && $user->group->activeUsers) {
                $ids = array_merge($ids, $user->group->activeUsers->pluck('id')->all());
            }
        }

        $ids = array_unique($ids);

        return $ids;
    }

    public function scopeOfGroupSupervisor($query, $id)
    {
        $ids = $this->relatedGroupIds($id);

        $query->whereIn($this->qualifyColumn('groupid'), $ids)
            ->orWhere($this->qualifyColumn('orderedby'), $id);
    }

    public function scopeOfGroupManager($query, $id)
    {
        $ids = $this->usersInRelatedGroups($id);

        $query->whereIn($this->qualifyColumn('orderedby'), $ids);
    }

    /**
     * @param $query
     * @param bool|string $search
     * @return mixed
     */
    public function scopeOfSearch($query, $search = false)
    {
        if ($search) {
            return $query->whereHas('appraisalType', function ($query) use ($search) {
                $query->ofDescription($search);
            })->orWhereHas('userData', function ($query) use ($search) {
                $query->ofName($search);
            })
                ->orWhere('appr_order.propaddress1', 'like', '%' . $search . '%')
                ->orWhere('appr_order.propcity', 'like', '%' . $search . '%')
                ->orWhere('appr_order.propstate', 'like', '%' . $search . '%')
                ->orWhere('appr_order.propzip', 'like', '%' . $search . '%')
                ->orWhere('appr_order.borrower', 'like', '%' . $search . '%')
                ->orWhere('appr_order.loanrefnum', 'like', '%' . $search . '%')
                ->orWhere('appr_order.id', 'like', '%' . $search . '%');
        }
    }

    // --------------------

    /**
     * Connection to User table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Users\User', 'orderedby');
    }

    /**
     * Connection to appr_uw table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apprUw()
    {
        return $this->hasMany(UW::class, 'order_id');
    }

    /**
     * Connection to appr_uw table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qcDataAnswers()
    {
        return $this->hasMany(DataAnswer::class, 'order_id')->with('question');
    }

    /**
     * Connection to appr_uw table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qcStats()
    {
        return $this->hasMany('App\Models\ManagerReports\QCReport\QCReport', 'order_id');
    }

    /**
     * Connection to appr_order_ucdp table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apprOrderUCDP()
    {
        return $this->hasOne('App\Models\Appraisal\UCDP\UCDP', 'order_id');
    }

    /**
     * Connection to status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo('App\Models\Customizations\Status', 'status');
    }

    /**
     * Connection to appr_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appraisalType()
    {
        return $this->belongsTo('App\Models\Customizations\Type', 'appr_type')->with('apprTypeOrderDocument');
    }

    /**
     * Connection to appr_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apprType()
    {
        return $this->belongsTo('App\Models\Customizations\Type', 'appr_type');
    }

    /**
     * Connection to loanpurpose table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanReason()
    {
        return $this->belongsTo('App\Models\Customizations\LoanReason', 'loantype');
    }

    /**
     * Connection to loanpurpose table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanType()
    {
        return $this->belongsTo('App\Models\Customizations\LoanType', 'loantype');
    }

    /**
     * Connection to api_user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiUser()
    {
        return $this->belongsTo('App\Models\Integrations\APIUsers\APIUser', 'api_user');
    }

    /**
     * Connection to user_group_lender table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lenderRecord()
    {
        return $this->belongsTo('App\Models\Management\WholesaleLenders\UserGroupLender', 'lender_id');
    }

    /**
     * Connection to user_groups table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupData()
    {
        return $this->belongsTo('App\Models\Clients\Client', 'groupid')
            ->with('adminTeamClient');
    }

    /**
     * Connection to amc table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function amc()
    {
        return $this->belongsTo('App\Models\Tiger\Amc', 'amc_id');
    }

    /**
     * Connection to admin_team_client table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function adminTeamClient()
    {
        return $this->hasOne('App\Models\Management\AdminTeamsManager\AdminTeamClient', 'user_group_id', 'groupid')->with('adminTeam');
    }

    /**
     * Connection to amc_registration table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function registration()
    {
        return $this->hasOne('App\Models\Customizations\AMCLicense', 'state', 'propstate');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userData()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'orderedby');
    }

    /**
    * Connection to order_log table
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
    public function lastLog()
    {
        return $this->hasMany('App\Models\Appraisal\OrderLog', 'orderid');
    }

    /**
     * Connection to user_groups table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprClient()
    {
        return $this->hasMany('App\Models\Clients\Client', 'id', 'groupid');
    }

    /**
     * Connection to order_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprStatus()
    {
        return $this->hasMany('App\Models\Customizations\Status', 'id', 'status');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'orderedby');
    }

    /**
     * Connection to appr_order_delay_code table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprOrderDelayCode()
    {
        return $this->hasMany('App\Models\Appraisal\DelayCodes\DelayCodes', 'order_id');
    }


    /**
     * Connection to appr_state_price_version table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprStatePricingVersion()
    {
        return $this->belongsTo('App\Models\Appraisal\StatePricingVersion', 'pricing_version');
    }

    /**
     * Connection to appr_order_xml_info table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function approrderXmlInfo()
    {
        return $this->hasOne('App\Models\Appraisal\ApprOrderXmlInfo', 'orderid');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDataByAssigned()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'assigned_by');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDataByAcceptedBy()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'acceptedby');
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userAcceptedBy()
    {
        return $this->hasOne('App\Models\Users\User', 'id', 'acceptedby');
    }

    /**
     * Connection to fnc_order_relation table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fnc()
    {
        return $this->hasOne('App\Models\Integrations\FNC\Relation', 'lni_oid');
    }

    /**
     * Connection to valutrac_order_relation table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function valuTrac()
    {
        return $this->hasOne('App\Models\Integrations\ValuTrac\OrderRelation', 'lni_oid');
    }

    /**
     * Connection to mercury_order_relation table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mercury()
    {
        return $this->hasOne('App\Models\Integrations\MercuryNetwork\OrderRelation', 'lni_oid');
    }

    /**
     * Connection to appr_appointment_schedule table
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function appointmentSchedule()
    {
        return $this->hasMany('App\Models\Appraisal\AppointmentSchedule', 'order_id');
    }

    /**
     * Connection to appr_order_files table
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function files()
    {
        return $this->hasMany('App\Models\Appraisal\OrderFile', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'order_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipCode()
    {
        return $this->hasOne(ZipCode::class, 'zip_code', 'propzip');
    }

    /*
     * Get Order  Data
     * @return mixed
     */
    public static function getOrderById($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * Connection to appr_fd_payments table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fdPayments()
    {
        return $this->hasOne('App\Models\Appraisal\ApprFDPayment', 'order_id');
    }

    /**
     * Connection to appr_cim_check_payments table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cimChechPayments()
    {
        return $this->hasOne('App\Models\Accounting\CimCheckPayment', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * Connection to appr_dashboard_delay_order table
     *
     */
    public function apprDashboardDelayOrder()
    {
        return $this->hasOne('App\Models\Appraisal\ApprDashboardDelayOrder', 'orderid');
    }

    /**
     * Get Filtered Data
     * @return mixed
     */
    public function appraisalPayment()
    {
        return $this->hasOne(AppraiserPayment::class, 'orderid', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function appraiserPayments()
    {
        return $this->hasMany(AppraiserPayment::class, 'apprid', 'id');
    }

    /**
     * @param $query
     * @param $from
     * @param $to
     * @param $type
     * @return mixed
     */
    public function scopeBatchCheckDates($query, $from, $to, $type)
    {
        if ($type == 'ordered') {
            return $query->whereBetween('ordereddate', [$from, $to]);
        }
        if ($type == 'delivered') {
            return $query->whereBetween('date_delivered', [$from, $to]);
        }
        return $query->where(function ($q) use ($from, $to) {
            return $q->whereBetween('ordereddate', [$from, $to])
                ->orWhereBetween('date_delivered', [$from, $to]);
        });
    }
    /**
     * Connection to appr_order_invites table
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function invites()
    {
        return $this->hasMany('App\Models\Appraisal\ApprOrderInvites', 'order_id');
    }
    /**
     * Connection to appr_order_invites table
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket\Ticket', 'orderid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprFdPayment()
    {
        return $this->hasOne(ApprFDPayment::class);
    }

    public function notificationGroupData()
    {
        return $this->belongsTo('App\Models\Clients\Client', 'groupid')
            ->with('adminTeamClient')->where('show_group_as_lender', '1');
    }

    /**
     * @param $query
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @return mixed
     */
    public function scopeGetDateCondition($query, $dateFrom, $dateTo, $dateType)
    {
        if ($dateType != 'fd.created_date') {
            return $query->whereBetween($dateType, [
                $dateFrom,
                $dateTo,
            ]);
        }
        return $query->whereHas('apprFdPayment', function ($q) use ($dateFrom, $dateTo) {
            return $q->whereBetween('created_date', [
                strtotime($dateFrom),
                strtotime($dateTo)
            ]);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderUserDocs()
    {
        return $this->hasOne(UserDoc::class, 'userid', 'acceptedby');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payablePaymentRecord()
    {
        return $this->hasOne(AccountingPayablePaymentRecord::class, 'orderid');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userGroupLogs()
    {
        return $this->belongsToMany('App\Models\UserGroupLog');
    }
}
