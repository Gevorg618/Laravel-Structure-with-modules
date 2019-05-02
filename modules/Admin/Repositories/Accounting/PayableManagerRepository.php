<?php

namespace Modules\Admin\Repositories\Accounting;

use DB;
use Session;
use App\Models\Documents\UserDoc;
use App\Models\Users\UserData;
use Yajra\DataTables\Datatables;
use App\Models\Appraisal\Order;
use App\Models\Accounting\AccountingPayablePaymentRecord;
use App\Models\AlternativeValuation\Order as AltOrder;
use App\Models\AlternativeValuation\SubOrder;
use App\Models\Appraisal\OrderAddFee;
use App\Models\Accounting\AccountingPayablePayment;
use App\Models\Appraisal\AppraiserPayment;
use App\Models\Appraisal\OrderLog;
use App\Models\Accounting\EmailNotification;
use App\Models\AlternativeValuation\AgentPayment;
use App\Models\AlternativeValuation\OrderLog as AltOrderLog;
use App\Services\CreateS3Storage;
use Modules\Admin\Repositories\Document\UserDocRepository;


class PayableManagerRepository
{
    public static $checkNumbers = [];

    public static $currentCheckNumber = null;

    protected static $appraiserSplits = [];

    public $orders;

    /**
     * PayableManagerRepository constructor.
     */

    private $createS3Storage;

    /**
     * Create a new instance of CreateS3Storage class.
     *
     * @return void
     */


    private $userDocRepository;

    /**
     * Create a new instance of UserDocRepository class.
     *
     * @return void
     */

    public function __construct()
    {
        // Get highest check number we currently have
        if (!self::$currentCheckNumber) {
            $max = AccountingPayablePaymentRecord::max('check_number');
            self::$currentCheckNumber = $max > 0 ? $max : 60000;
        }

        $this->createS3Storage = new CreateS3Storage();
        $this->userDocRepository = new UserDocRepository();
    }

    /**
     * get check number
     *
     * @param $appraiserId integer
     *
     * @return checkNumbers integer
     */
    public static function getCheckNumber($appraiserId)
    {
        if (isset(self::$checkNumbers[$appraiserId])) {
            return self::$checkNumbers[$appraiserId];
        }
        self::$currentCheckNumber = self::$currentCheckNumber + 1;
        self::$checkNumbers[$appraiserId] = self::$currentCheckNumber;

        return self::$checkNumbers[$appraiserId];
    }

    /**
     *  generate dattable by type
     *
     * @param $data array
     *
     * @return datatable json
     */
    public function generateDatatables($data)
    {
        $requestType = $data['request_type'];

        Session::forget('data');
        Session::push('data', $data);

        switch ($requestType) {
            case 'apprasial':
                    return $this->apprasialDatatable($data);
                break;
            case 'alt':
                    return $this->altOrderDatatable($data);
                break;
            case 'trimerge':
                    return $this->altSubOrderDatatable($data);
                break;
            case 'fees':
                    return $this->feeOrderDatatable($data);
                break;
        }
    }

    /**
     * appply payment
     */
    public function applyPayment($data)
    {
        $requestType = $data['request_type'];
        $items = $data['records'];

        $requestData = Session::get('data')[0];
        switch ($requestType) {
            case 'apprasial':
                    return [ 'dataCsv' => $this->downloadCsv($requestData, $items), 'items' => $this->apprasialApplyPayment($items)];
                break;
            case 'alt':
                    return [ 'dataCsv' => $this->downloadCsv($requestData, $items), 'items' => $this->altOrderApplyPayment($items)];
                break;
            case 'trimerge':
                    return [ 'dataCsv' => $this->downloadCsv($requestData, $items), 'items' => $this->altSubOrderApplyPayment($items)];
                break;
            case 'fees':
                    return [ 'dataCsv' => $this->downloadCsv($requestData, $items), 'items' => $this->feeOrderApplyPayment($items)];
                break;
        }
    }

    /**
     *  generate csv file  by request type
     *
     * @param $data array
     *
     * @return datatable json
     */
    public function downloadCsv($data, $checkedItems = null)
    {
        $path = config('excel.export.store.path');
        $isDirectory = \File::isDirectory($path);
        $isDirectory ? \File::deleteDirectory($path): false;

        $requestType = $data['request_type'];

        if ($checkedItems) {
            $data['checked_items'] = $checkedItems;
        } else {
            $data['checked_items']  =  explode(",", $data['checked_items']);
        }

        switch ($requestType) {
            case 'apprasial':
                    return $this->apprasialDownloadCsv($data);
                break;
            case 'alt':
                    return $this->altOrderDownloadCsv($data);
                break;
            case 'trimerge':
                    return $this->altSubOrderDownloadCsv($data);
                break;
            case 'fees':
                    return $this->feeOrderDownloadCsv($data);
                break;
        }
    }

    /**
     * generate aprrasial order dtata for download csv
     *
     * @param $data
     */
    public function apprasialDownloadCsv($data)
    {
        $orders = $this->getOrders($data)->get();

        $list = [];

        $headers = $this->includingHeaders();

        foreach ($orders as $order) {
            $userData = $order->userData ? $order->userData : null;

            if (!$userData) {
                continue;
            }
            if ($userData->payable_company) {
                $payableAddress  = $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
            } else {
                $payableAddress  = $userData->comp_address ? $userData->comp_address : 'N/A';
            }

            if ($userData->payable_company) {
                $city = $userData->payable_city;
            } else {
                $city = $userData->comp_city ? $userData->comp_city : 'N/A';
            }

            if ($userData->payable_company) {
                $state = $userData->payable_state;
            } else {
                $state = $userData->comp_state ? $userData->comp_state : 'N/A';
            }

            if ($userData->payable_company) {
                $zip = $userData->payable_zip;
            } else {
                $zip = $userData->comp_zip ? $userData->comp_zip : 'N/A';
            }

            $list[] = [
                'user_id' => $order->userData->user_id,
                'name' => $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A',
                'payable_address' => $payableAddress,
                'payable_city' => $city,
                'payable_state' => $state,
                'payable_zip' => $zip,
                'totalSplit' => currency($this->getAppraiserTotalSplit($data, $order->acceptedby)),
                'check_number' => $this->getCheckNumber($order->acceptedby),
                'pay_date' => date('Y-m-d', strtotime('today')),
                'date_delivered' => $order->date_delivered ? $order->date_delivered : 'N/A',
                'id' => $order->id,
                'propaddress1' => $order->propaddress1 ? $order->propaddress1 : 'N/A',
                'split_amount' => currency($order->split_amount)
            ];
        }

        $dataCsv = [];

        foreach ($list as $key => $value) {
            foreach ($headers as $keyHead => $valueHead) {
                $dataCsv[$key][$valueHead] =  $value[$keyHead];
            }
        }

        return [ 'data_csv' => $dataCsv, 'file_name' => sprintf('apprasial_orders_%s.xlsx', date('m_d_Y_H_m_s'))];
    }

    /**
     * generate aprrasial order dtata for download csv
     *
     * @param $data
     */
    public function altOrderDownloadCsv($data)
    {
        $orders = $this->getAltOrders($data)->get();

        $list = [];

        $headers = $this->includingHeaders();

        foreach ($orders as $order) {
            $userData = $order->agentData ? $order->agentData : null;

            if (!$userData) {
                continue;
            }
            if ($userData->payable_company) {
                $payableAddress  = $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
            } else {
                $payableAddress  = $userData->comp_address ? $userData->comp_address : 'N/A';
            }

            if ($userData->payable_company) {
                $city = $userData->payable_city;
            } else {
                $city = $userData->comp_city ? $userData->comp_city : 'N/A';
            }

            if ($userData->payable_company) {
                $state = $userData->payable_state;
            } else {
                $state = $userData->comp_state ? $userData->comp_state : 'N/A';
            }

            if ($userData->payable_company) {
                $zip = $userData->payable_zip;
            } else {
                $zip = $userData->comp_zip ? $userData->comp_zip : 'N/A';
            }

            $list[] = [
                'user_id' => $userData->user_id,
                'name' => $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A',
                'payable_address' => $payableAddress,
                'payable_city' => $city,
                'payable_state' => $state,
                'payable_zip' => $zip,
                'totalSplit' => currency($this->getAltOrders($data, $order->acceptedby)->first()->totalSplit),
                'check_number' => $this->getCheckNumber($order->acceptedby),
                'pay_date' => date('Y-m-d', strtotime('today')),
                'date_delivered' => $order->submitted ? $order->submitted : 'N/A',
                'id' => $order->id,
                'propaddress1' => $order->propaddress1 ? $order->propaddress1 : 'N/A',
                'split_amount' => currency($order->split_amount)
            ];
        }

        $dataCsv = [];

        foreach ($list as $key => $value) {
            foreach ($headers as $keyHead => $valueHead) {
                $dataCsv[$key][$valueHead] =  $value[$keyHead];
            }
        }

        return [ 'data_csv' => $dataCsv, 'file_name' => sprintf('alternative_orders_%s.xlsx', date('m_d_Y_H_m_s'))];
    }

    /**
     * generate aprrasial order dtata for download csv
     *
     * @param $data
     */
    public function altSubOrderDownloadCsv($data)
    {
        $orders = $this->getAltSubOrders($data)->get();

        $list = [];

        $headers = $this->includingHeaders();

        foreach ($orders as $order) {
            $userData = $order->agentData ? $order->agentData : null;

            if (!$userData) {
                continue;
            }
            if ($userData->payable_company) {
                $payableAddress  = $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
            } else {
                $payableAddress  = $userData->comp_address ? $userData->comp_address : 'N/A';
            }

            if ($userData->payable_company) {
                $city = $userData->payable_city;
            } else {
                $city = $userData->comp_city ? $userData->comp_city : 'N/A';
            }

            if ($userData->payable_company) {
                $state = $userData->payable_state;
            } else {
                $state = $userData->comp_state ? $userData->comp_state : 'N/A';
            }

            if ($userData->payable_company) {
                $zip = $userData->payable_zip;
            } else {
                $zip = $userData->comp_zip ? $userData->comp_zip : 'N/A';
            }

            $list[] = [
                'user_id' => $userData->user_id,
                'name' => $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A',
                'payable_address' => $payableAddress,
                'payable_city' => $city,
                'payable_state' => $state,
                'payable_zip' => $zip,
                'totalSplit' => currency($this->getAltOrders($data, $order->acceptedby)->first()->totalSplit),
                'check_number' => $this->getCheckNumber($order->acceptedby),
                'pay_date' => date('Y-m-d', strtotime('today')),
                'date_delivered' => $order->submitted ? $order->submitted : 'N/A',
                'id' => $order->id,
                'propaddress1' => $order->propaddress1 ? $order->propaddress1 : 'N/A',
                'split_amount' => currency($order->split_amount)
            ];
        }

        $dataCsv = [];

        foreach ($list as $key => $value) {
            foreach ($headers as $keyHead => $valueHead) {
                $dataCsv[$key][$valueHead] =  $value[$keyHead];
            }
        }

        return [ 'data_csv' => $dataCsv, 'file_name' => sprintf('alternative_sub_orders_%s.xlsx', date('m_d_Y_H_m_s'))];
    }

    /**
     * generate aprrasial order dtata for download csv
     *
     * @param $data
     */
    public function feeOrderDownloadCsv($data)
    {
        $orders = $this->getFeeOrders($data)->get();

        $list = [];

        $headers = $this->includingHeaders();

        foreach ($orders as $order) {
            $userData = $order->userData ? $order->userData : null;

            if (!$userData) {
                continue;
            }
            if ($userData->payable_company) {
                $payableAddress  = $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
            } else {
                $payableAddress  = $userData->comp_address ? $userData->comp_address : 'N/A';
            }

            if ($userData->payable_company) {
                $city = $userData->payable_city;
            } else {
                $city = $userData->comp_city ? $userData->comp_city : 'N/A';
            }

            if ($userData->payable_company) {
                $state = $userData->payable_state;
            } else {
                $state = $userData->comp_state ? $userData->comp_state : 'N/A';
            }

            if ($userData->payable_company) {
                $zip = $userData->payable_zip;
            } else {
                $zip = $userData->comp_zip ? $userData->comp_zip : 'N/A';
            }

            $list[] = [
                'user_id' => $order->userData->user_id,
                'name' => $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A',
                'payable_address' => $payableAddress,
                'payable_city' => $city,
                'payable_state' => $state,
                'payable_zip' => $zip,
                'totalSplit' => currency($this->getFeeOrders($data, $order->apprid)->first()->totalSplit),
                'check_number' => $this->getCheckNumber($order->apprid),
                'pay_date' => date('Y-m-d', strtotime('today')),
                'date_delivered' => $order->order->date_delivered ? $order->order->date_delivered : 'N/A',
                'id' => $order->order->id,
                'propaddress1' => $order->order->propaddress1 ? $order->order->propaddress1 : 'N/A',
                'split_amount' => currency($order->order->split_amount)
            ];
        }

        $dataCsv = [];

        foreach ($list as $key => $value) {
            foreach ($headers as $keyHead => $valueHead) {
                $dataCsv[$key][$valueHead] =  $value[$keyHead];
            }
        }

        return [ 'data_csv' => $dataCsv, 'file_name' => sprintf('fees_orders_%s.xlsx', date('m_d_Y_H_m_s'))];
    }


    /**
     * headers csv
     *
     * @param $data
     */
    public function includingHeaders()
    {
        return [
                'user_id' => 'UID',
                'name' => 'Name',
                'payable_address' => 'Address',
                'payable_city' => 'City',
                'payable_state' => 'State',
                'payable_zip' => 'ZIP',
                'totalSplit' => 'Check Amount',
                'check_number' => 'Check Number',
                'pay_date' => 'Pay Date',
                'date_delivered' => 'Delivery Date',
                'id' => 'OID',
                'propaddress1' => 'Address',
                'split_amount' => 'Split'
        ];
    }

    /**
     *  generate fees orders datatable
     *
     * @param $data array
     *
     * @return  fees orders datatable json
     */
    public function feeOrderDatatable($data)
    {
        $orders = $this->getFeeOrders($data);

        return  Datatables::of($orders)
                ->editColumn('checkbox', function ($order) use ($data) {
                    $checkAmount = currency($this->getFeeOrders($data, $order->apprid)->first()->totalSplit);
                    return view('admin::accounting.payable-manager.partials._checkbox', compact('order','checkAmount'))->render();
                })
                ->editColumn('uid', function ($order) {
                    return $order->userData->user_id;
                })
                ->editColumn('name', function ($order) {
                    $userData = $order->userData ? $order->userData : null;
                    return $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A';
                })
                ->editColumn('payee_name', function ($order) {
                    $userData = $order->userData ? $order->userData : null;
                    return $userData ? $userData->payable_company: 'N/A';
                })
                ->editColumn('address', function ($order) {
                    $userData = $order->userData ? $order->userData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
                    } else {
                        return $userData->comp_address;
                    }
                })
                ->editColumn('city', function ($order) {
                    $userData = $order->userData ? $order->userData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_city;
                    } else {
                        return $userData->comp_city;
                    }
                })
                ->editColumn('state', function ($order) {
                    $userData = $order->userData ? $order->userData : null;

                    if ($userData->payable_company) {
                        return $userData->payable_state;
                    } else {
                        return $userData->comp_state;
                    }
                })
                ->editColumn('zip', function ($order) {
                    $userData = $order->userData ? $order->userData : null;

                    if ($userData->payable_company) {
                        return $userData->payable_zip;
                    } else {
                        return $userData->comp_zip;
                    }
                })
                ->editColumn('ein', function ($order) {
                    $userData = $order->userData ? $order->userData : null;

                    $ein = $userData->ein;

                    $taxClass = $userData->tax_class ? $userData->tax_class : 'sole';

                    $result = $this->asTaxNumber($ein, $taxClass);

                    return  $result ? $result : 'n/a';
                })
                ->editColumn('w9', function ($order) {
                    $document = $order->userAcceptedBy->w9;
                    if ($document) {
                        return view('admin::accounting.payable-manager.partials._s3_link',compact('document'))->render();
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('check_amount', function ($order) use ($data) {
                    return currency($this->getFeeOrders($data, $order->apprid)->first()->totalSplit);
                })
                ->editColumn('check_number', function ($order) {
                    return $this->getCheckNumber($order->order->acceptedby);
                })
                ->editColumn('pay_date', function ($order) {
                    return date('Y-m-d', strtotime('today'));
                })
                ->editColumn('deliver_date', function ($order) {
                    return $order->order->date_delivered ? $order->order->date_delivered : 'N/A';
                })
                ->editColumn('id', function ($order) {
                    return $order->order->id;
                })
                ->editColumn('status', function ($order) {
                    return $order->order->apprStatus->first()->descrip;
                })
                ->editColumn('address1', function ($order) {
                    return $order->order->propaddress1 ? $order->order->propaddress1 : 'N/A';
                })
                ->editColumn('split_amount', function ($order) {
                    return currency($order->order->split_amount);
                })
                ->editColumn('balance', function ($order) {
                    return currency($order->order->balance);
                })
                ->addColumn('count', function ($order) {
                    return AppraiserPayment::where('apprid', $order->apprid)->count();
                })
                ->rawColumns(['checkbox', 'w9'])
                ->make(true);
    }

    /**
     *  get fees data orders for generating datatable
     *
     * @param $data array
     * @param $apprId integer
     *
     * @return  fees orders datatable json
     */
    public function getFeeOrders($data, $apprId = null)
    {
        $dateRange = explode("-", $data['daterange']);

        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));

        $orders = OrderAddFee::with(['userData', 'orderStatus', 'apprType', 'userAcceptedBy', 'userAcceptedBy.w9'])->where('apprid', '>', '0')
                    ->where('paid', '0000-00-00 00:00:00')
                    ->whereBetween('added', [$dateFrom , $dateTo]);

        if ($apprId) {
            $orders = $orders->where('apprid', $apprId)->select(\DB::raw(
                "SUM(amount) as totalSplit"
            ));
        }

        // Balance filter
        if ($data['balance']) {
            switch ($data['balance']) {
                case 'balance':
                    $orders = $orders->where('order', function ($query) {
                        $query->whereRaw('(invoicedue-paid_amount) > 0');
                    });
                    break;
                case 'refund':
                    $orders = $orders->where('order', function ($query) {
                        $query->whereRaw('(invoicedue-paid_amount) < 0');
                    });
                    break;
                case 'full':
                    $orders = $orders->where('order', function ($query) {
                        $query->whereRaw('(invoicedue-paid_amount) = 0');
                    });
                    break;
            }
        }

        if ($data['free_text']) {

            // Explode new lines
            $freeTexts = explode("\n", $data['free_text']);
            $freeTexts = $this->cleanTextForSearch($freeTexts);

            if ($freeTexts) {
                $orders = $orders->whereIn('apprid', $freeTexts)->orWhereHas('userData', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->whereRaw("CONCAT(firstname,' ',lastname) like ?", ["%{$word}%"]);
                    }
                })->orWhereHas('user', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->where('email', 'LIKE', '%'.$word.'%');
                    }
                });
            }
        }

        // Orders id's
        if (isset($data['checked_items']) && $data['checked_items'] && count($data['checked_items']) && $data['checked_items'][0] != "") {
            $orders = $orders->whereIn('id', $data['checked_items']);
        }

        // Specific status
        if ($data['status']) {
            $orders = $orders->where('order', function ($query) {
                $query->where('status', $data['status']);
            });
        }

        // State conditions
        if (isset($data['states']) &&  $data['states'] && count($data['states'])) {
            $states = $data['states'];
            $orders = $orders->whereHas('userData', function ($query) use ($states) {
                $query->whereIn('payable_state', $states);
            });
        }

        // Add clients list
        if (isset($data['client']) && $data['client'] && count($data['client'])) {
            $clients = $data['client'];
            $orders = $orders->whereHas('order', function ($query) use ($clients) {
                $query->whereIn('groupid', $clients);
            });
        }

        return $orders;
    }

    /**
     * generate datatable for alternative sub orders
     *
     * @param $data array
     *
     * @return datatable json
     */
    public function altSubOrderDatatable($data)
    {
        $orders = $this->getAltSubOrders($data);

        return  Datatables::of($orders)
                ->editColumn('checkbox', function ($order) use ($data) {
                    $checkAmount =  currency($this->getAltSubOrders($data, $order->acceptedby)->first()->totalSplit);
                    return view('admin::accounting.payable-manager.partials._checkbox', compact('order', 'checkAmount'))->render();
                })
                ->editColumn('uid', function ($order) {
                    return $order->agentData->user_id;
                })
                ->editColumn('name', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    return $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A';
                })
                ->editColumn('payee_name', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    return $userData ? $userData->payable_company: 'N/A';
                })
                ->editColumn('address', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
                    } else {
                        return $userData->comp_address;
                    }
                })
                ->editColumn('city', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_city;
                    } else {
                        return $userData->comp_city;
                    }
                })
                ->editColumn('state', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_state;
                    } else {
                        return $userData->comp_state;
                    }
                })
                ->editColumn('zip', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_zip;
                    } else {
                        return $userData->comp_zip;
                    }
                })
                ->editColumn('ein', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    $ein = $userData->ein;
                    $taxClass = $userData->tax_class ? $userData->tax_class : 'sole';
                    $result = $this->asTaxNumber($ein, $taxClass);
                    return  $result ? $result : 'n/a';
                })
                ->editColumn('w9', function ($order) {
                    $document = $order->userAcceptedBy->w9;
                    if ($document) {
                        return view('admin::accounting.payable-manager.partials._s3_link',compact('document'))->render();
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('check_amount', function ($order) use ($data) {
                    return currency($this->getAltSubOrders($data, $order->acceptedby)->first()->totalSplit);
                })
                ->editColumn('check_number', function ($order) {
                    return $this->getCheckNumber($order->acceptedby);
                })
                ->editColumn('pay_date', function ($order) {
                    return date('Y-m-d', strtotime('today'));
                })
                ->editColumn('submitted', function ($order) {
                    return $order->submitted;
                })
                ->editColumn('id', function ($order) {
                    return $order->id;
                })
                ->editColumn('status', function ($order) {
                    return $order->orderStatus->name;
                })
                ->editColumn('address1', function ($order) {
                    return $order->propaddress1 ? $order->propaddress1 : 'N/A';
                })
                ->editColumn('split_amount', function ($order) {
                    return currency($order->split_amount);
                })
                ->editColumn('balance', function ($order) {
                    return currency($order->balance);
                })
                ->addColumn('count', function ($order) {
                    return AgentPayment::where('agentid', $order->acceptedby)->count();
                })
                ->rawColumns(['checkbox', 'w9'])
                ->make(true);
    }

    /**
     * get alt sub orders for creating datatable
     *
     * @param $data array
     * @param $apprId integer
     *
     * @return $orders object
     */
    public function getAltSubOrders($data, $apprId = null)
    {
        $dateRange = explode("-", $data['daterange']);

        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));

        $orders = SubOrder::with(['userData', 'orderStatus', 'userAcceptedBy', 'userAcceptedBy.w9'])->where('acceptedby', '>', '0')
                    ->whereNull('agent_paid')
                    ->whereIn('status', [8,20])
                    ->where('type_id', '1')
                    ->whereBetween('submitted', [$dateFrom , $dateTo]);

        if ($apprId) {
            $orders = $orders->where('acceptedby', $apprId)->select(\DB::raw(
                "SUM(split_amount) as totalSplit"
            ));
        }

        // Balance filter
        if ($data['balance']) {
            switch ($data['balance']) {
                case 'balance':
                    $orders = $orders->whereRaw('(invoicedue-paid_amount) > 0');
                    break;
                case 'refund':
                    $orders = $orders->whereRaw('(invoicedue-paid_amount) < 0');
                    break;
                case 'full':
                    $orders = $orders->whereRaw('(invoicedue-paid_amount) = 0');
                    break;
            }
        }

        if ($data['free_text']) {

            // Explode new lines
            $freeTexts = explode("\n", $data['free_text']);
            $freeTexts = $this->cleanTextForSearch($freeTexts);

            if ($freeTexts) {
                $orders =  $orders->whereIn('acceptedby', $freeTexts)->orWhereHas('agentData', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->whereRaw("CONCAT(firstname,' ',lastname) like ?", ["%{$word}%"]);
                    }
                })->orWhereHas('agent', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->where('email', 'LIKE', '%'.$word.'%');
                    }
                });
            }
        }

        // Orders id's
        if (isset($data['checked_items']) && $data['checked_items'] && count($data['checked_items']) && $data['checked_items'][0] != "") {
            $orders = $orders->whereIn('id', $data['checked_items']);
        }

        // Specific status
        if ($data['status']) {
            $orders = $orders->where('status', $data['status']);
        }

        // State conditions
        if (isset($data['states']) &&  $data['states'] && count($data['states'])) {
            $states = $data['states'];
            $orders = $orders->whereHas('agentData', function ($query) use ($states) {
                $query->whereIn('payable_state', $states);
            });
        }

        // Add clients list
        if (isset($data['client']) && $data['client'] && count($data['client'])) {
            $clients = $data['client'];
            $orders = $orders->whereHas('order', function ($query) use ($clients) {
                $query->whereIn('groupid', $clients);
            });
        }

        return $orders;
    }

    /**
     *  generate alternative order datatable
     *
     * @param $data array
     *
     * @return  fees orders datatable json
     */
    public function altOrderDatatable($data)
    {
        $orders = $this->getAltOrders($data);

        return  Datatables::of($orders)
                ->editColumn('checkbox', function ($order) use  ($data) {
                    $checkAmount = currency($this->getAltOrders($data, $order->acceptedby)->first()->totalSplit);
                    return view('admin::accounting.payable-manager.partials._checkbox', compact('order', 'checkAmount'))->render();
                })
                ->editColumn('uid', function ($order) {
                    return $order->agentData->user_id;
                })
                ->editColumn('name', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    return $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A';
                })
                ->editColumn('payee_name', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    return $userData ? $userData->payable_company: 'N/A';
                })
                ->editColumn('address', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
                    } else {
                        return $userData->comp_address;
                    }
                })
                ->editColumn('city', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_city;
                    } else {
                        return $userData->comp_city;
                    }
                })
                ->editColumn('state', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_state;
                    } else {
                        return $userData->comp_state;
                    }
                })
                ->editColumn('zip', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    if ($userData->payable_company) {
                        return $userData->payable_zip;
                    } else {
                        return $userData->comp_zip;
                    }
                })
                ->editColumn('ein', function ($order) {
                    $userData = $order->agentData ? $order->agentData : null;
                    $ein = $userData->ein;
                    $taxClass = $userData->tax_class ? $userData->tax_class : 'sole';
                    $result = $this->asTaxNumber($ein, $taxClass);
                    return  $result ? $result : 'n/a';
                })
                ->editColumn('w9', function ($order) {
                    $document = $order->userAcceptedBy->w9;
                    if ($document) {
                        return view('admin::accounting.payable-manager.partials._s3_link', compact('document'))->render();
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('check_amount', function ($order) use ($data) {
                    return currency($this->getAltOrders($data, $order->acceptedby)->first()->totalSplit);
                })
                ->editColumn('check_number', function ($order) {
                    return $this->getCheckNumber($order->acceptedby);
                })
                ->editColumn('pay_date', function ($order) {
                    return date('Y-m-d', strtotime('today'));
                })
                ->editColumn('submitted', function ($order) {
                    return $order->submitted;
                })
                ->editColumn('id', function ($order) {
                    return $order->id;
                })
                ->editColumn('status', function ($order) {
                    return $order->orderStatus->name;
                })
                ->editColumn('address1', function ($order) {
                    return $order->propaddress1 ? $order->propaddress1 : 'N/A';
                })
                ->editColumn('split_amount', function ($order) {
                    return currency($order->split_amount);
                })
                ->editColumn('balance', function ($order) {
                    return currency($order->balance);
                })
                ->addColumn('count', function ($order) {
                    return AppraiserPayment::where('apprid', $order->acceptedby)->count();
                })
                ->rawColumns(['checkbox', 'W9'])
                ->make(true);
    }

    /**
     *  get alterbative orders data for generating datatable
     *
     * @param $data array
     * @param $apprId integer
     *
     * @return  fees orders datatable json
     */
    public function getAltOrders($data, $apprId = null)
    {
        $dateRange = explode("-", $data['daterange']);

        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));

        $orders = AltOrder::with(['userData', 'orderStatus', 'userAcceptedBy', 'userDataByAcceptedBy', 'userAcceptedBy.w9'])->where('acceptedby', '>', '0')
                ->whereNull('agent_paid')
                ->whereIn('status', [8,20])
                ->where('type_id', '1')
                ->whereBetween('submitted', [$dateFrom , $dateTo]);

        if ($apprId) {
            $orders = $orders->where('acceptedby', $apprId)->select(\DB::raw(
                "SUM(split_amount) as totalSplit"
            ));
        }

        // Balance filter
        if ($data['balance']) {
            switch ($data['balance']) {
                case 'balance':
                    $orders->whereRaw('(invoicedue-paid_amount) > 0');
                    break;
                case 'refund':
                    $orders->whereRaw('(invoicedue-paid_amount) < 0');
                    break;
                case 'full':
                    $orders->whereRaw('(invoicedue-paid_amount) = 0');
                    break;
            }
        }

        if ($data['free_text']) {

            // Explode new lines
            $freeTexts = explode("\n", $data['free_text']);
            $freeTexts = $this->cleanTextForSearch($freeTexts);

            if ($freeTexts) {
                $orders = $orders->whereIn('acceptedby', $freeTexts)->orWhereHas('agentData', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->whereRaw("CONCAT(firstname,' ',lastname) like ?", ["%{$word}%"]);
                    }
                })->orWhereHas('agent', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->where('email', 'LIKE', '%'.$word.'%');
                    }
                });
            }
        }

        // Specific status
        if ($data['status']) {
            $orders->where('status', $data['status']);
        }

        // Orders id's
        if (isset($data['checked_items']) && $data['checked_items'] && count($data['checked_items']) && $data['checked_items'][0] != "") {
            $orders = $orders->whereIn('id', $data['checked_items']);
        }

        // State conditions
        if (isset($data['states']) && $data['states'] && count($data['states'])) {
            $states = $data['states'];
            $orders = $orders->whereHas('agentData', function ($query) use ($states) {
                $query->whereIn('payable_state', $states);
            });
        }

        // Add clients list
        if (isset($data['client']) && $data['client'] && count($data['client'])) {
            $orders = $orders->whereIn('groupid', $data['client']);
        }

        return $orders;
    }

    protected function getAppraiserTotalSplit($data, $id)
    {
        if(isset(static::$appraiserSplits[$id])) {
          return static::$appraiserSplits[$id];
        }

        $amount = optional($this->getOrders($data, $id)->first())->totalSplit;

        static::$appraiserSplits[$id] = $amount;

        return $amount;
    }


    /**
     *  get apprasial orders data for generating datatable
     *
     * @param $data array
     *
     * @return  fees orders datatable json
     */
    public function apprasialDatatable($data)
    {
        $orders = $this->getOrders($data);

        return  Datatables::of($orders)
                ->editColumn('checkbox', function ($order) use ($data) {
                    $checkAmount = currency($this->getAppraiserTotalSplit($data, $order->acceptedby));
                    return view('admin::accounting.payable-manager.partials._checkbox', compact('order', 'checkAmount'))->render();
                })
                ->editColumn('uid', function ($order) {
                    return $order->userAcceptedBy->userData->user_id;
                })
                ->editColumn('name', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    return $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A';
                })
                ->editColumn('payee_name', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    return $userData ? $userData->payable_company: 'N/A';
                })
                ->editColumn('address', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    if ($userData->payable_company) {
                        return $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
                    } else {
                        return $userData->comp_address;
                    }
                })
                ->editColumn('city', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    if ($userData->payable_company) {
                        return $userData->payable_city;
                    } else {
                        return $userData->comp_city;
                    }
                })
                ->editColumn('state', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    if ($userData->payable_company) {
                        return $userData->payable_state;
                    } else {
                        return $userData->comp_state;
                    }
                })
                ->editColumn('zip', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    if ($userData->payable_company) {
                        return $userData->payable_zip;
                    } else {
                        return $userData->comp_zip;
                    }
                })
                ->editColumn('ein', function ($order) {
                    $userData = $order->userAcceptedBy->userData;
                    $ein = $userData->ein;
                    $taxClass = $userData->tax_class ? $userData->tax_class : 'sole';
                    $result = $this->asTaxNumber($ein, $taxClass);
                    return  $result ? $result : 'n/a';
                })
                ->editColumn('w9', function ($order) {
                    $document = $order->userAcceptedBy->w9;
                    if ($document) {
                        return view('admin::accounting.payable-manager.partials._s3_link',compact('document'))->render();
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('check_amount', function ($order) use ($data) {
                    return currency($this->getAppraiserTotalSplit($data, $order->acceptedby));
                })
                ->editColumn('check_number', function ($order) {
                    return $this->getCheckNumber($order->acceptedby);
                })
                ->editColumn('pay_date', function ($order) {
                    return date('Y-m-d', strtotime('today'));
                })
                ->editColumn('date_delivered', function ($order) {
                    return date('m/d/Y', strtotime($order->date_delivered));
                })
                ->editColumn('id', function ($order) {
                    return $order->id;
                })
                ->editColumn('status', function ($order) {
                    return $order->orderStatus->descrip;
                })
                ->editColumn('address1', function ($order) {
                    return $order->propaddress1 ? $order->propaddress1 : 'N/A';
                })
                ->editColumn('split_amount', function ($order) {
                    return currency($order->split_amount);
                })
                ->editColumn('balance', function ($order) {
                    return currency($order->balance);
                })
                ->addColumn('count', function ($order) {
                    return $order->appraiser_payments_count;
                })
                ->rawColumns(['checkbox', 'split_amount', 'balance', 'check_amount', 'w9'])
                ->make(true);
    }

    /**
     *  get apprasial orders data for generating datatable
     *
     * @param $data array
     * @param $apprId integer
     *
     * @return  fees orders datatable json
     */
    public function getOrders($data, $apprId = null)
    {
        $dateRange = explode("-", $data['daterange']);

        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));

        $orders = Order::withCount(['appraiserPayments'])
            ->where('acceptedby', '>', 0)
            ->where('appr_paid', null)
            ->where('submitted', '!=', '0000-00-00 00:00:00')
            ->whereNotNull('date_delivered')
            ->whereIn('status', [6,14,17,18,20])
            ->where('hide_from_payables', 0)
            ->whereBetween('date_delivered', [$dateFrom , $dateTo])
            ->whereHas('userAcceptedBy.userData', function($query) {
              $query->whereNotNull('user_id');
            })
            ->orderBy('acceptedby');

        if ($apprId) {
            $orders->where('acceptedby', $apprId)->addSelect(DB::raw(
                "SUM(split_amount) as totalSplit"
            ));
        } else {
            $orders->with(['userData', 'orderStatus', 'apprType', 'userAcceptedBy', 'userAcceptedBy.userData', 'userAcceptedBy.w9', 'userAcceptedBy.groups', 'userAcceptedBy.wholesaleLendersManager']);
        }

        // Balance filter
        if ($data['balance']) {
            switch ($data['balance']) {
                case 'balance':
                    $orders = $orders->whereRaw('(invoicedue-paid_amount) > 0');
                    break;
                case 'refund':
                    $orders = $orders->whereRaw('(invoicedue-paid_amount) < 0');
                    break;
                case 'full':
                    $orders = $orders->whereRaw('(invoicedue-paid_amount) = 0');
                    break;
            }
        }

        if ($data['free_text']) {
            // Explode new lines
            $freeTexts = explode("\n", $data['free_text']);
            $freeTexts = $this->cleanTextForSearch($freeTexts);

            if ($freeTexts) {
                $orders = $orders->whereIn('acceptedby', $freeTexts)->orWhereHas('userAcceptedBy.userData', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->whereRaw("CONCAT(firstname,' ',lastname) like ?", ["%{$word}%"]);
                    }
                })->orWhereHas('userAcceptedBy', function ($query) use ($freeTexts) {
                    foreach ($freeTexts as $word) {
                        $query->where('email', 'LIKE', '%'.$word.'%');
                    }
                });
            }
        }

        // Orders id's
        if (isset($data['checked_items']) && $data['checked_items'] && count($data['checked_items']) && $data['checked_items'][0] != "") {
            $orders = $orders->whereIn('id', $data['checked_items']);
        }

        // Specific status
        if (isset($data['status']) && $data['status']) {
            $orders = $orders->where('status', $data['status']);
        }

        // State conditions
        if (isset($data['states']) && $data['states'] && count($data['states'])) {
            $states = $data['states'];
            $orders = $orders->whereHas('userAcceptedBy.userData', function ($query) use ($states) {
                $query->whereIn('payable_state', $states);
            });
        }

        // Add clients list
        if (isset($data['client']) && $data['client'] && count($data['client'])) {
            $orders = $orders->whereIn('groupid', $data['client']);
        }

        return $orders;
    }

    /**
     *  text clean
     *
     * @param $text string
     *
     * @return  $text string
     */
    public function cleanTextForSearch($text)
    {
        if (is_array($text)) {
            $fresh = [];
            foreach ($text as $i => $t) {
                $t = str_replace(array("\r", "\t", "\n"), array(''), $t);
                $t = trim($t);
                if (!$t) {
                    continue;
                }
                $fresh[$i] = $t;
            }
            $text = $fresh;
        } else {
            $text = trim($text);
            $text = str_replace(array("\r", "\t", "\n"), array(''), $text);
            $text = trim($text);
        }

        return $text;
    }

    /**
     * Format the tax id number for a user based on the tax class
     * default to sole to format as SSN
     * @return string
     */
    public function asTaxNumber($value, $type='sole')
    {
        $taxId = $value;
        $taxId = preg_replace('/[^\d]/', '', $taxId);

        if ($this->taxClassFormatEIN($type)) {
            $taxId = preg_replace('/^(\d{2})(\d{7})$/', '$1-$2', $taxId);
        } else {
            $taxId = preg_replace('/^(\d{3})(\d{2})(\d{4})$/', '$1-$2-$3', $taxId);
        }

        return $taxId;
    }

    /**
     * Checks the type of format to use
     * based on the tax class type
     * @return bool
     */
    protected function taxClassFormatEIN($taxClass)
    {
        $taxClassType = $this->getUserTaxClasses();
        unset($taxClassType['sole'], $taxClassType['quickbooksssn']);

        if (in_array($taxClass, array_keys($taxClassType))) {
            return true;
        }

        return false;
    }

    protected function getUserTaxClasses()
    {
        return [
            'sole' => 'Sole Proprietor/ Individual - SSN',
            'soleein' => 'Sole Proprietor/ Individual - EIN',
            'ccorp' => 'C-Corp',
            'scorp' => 'S-Corp',
            'partnership' => 'Partnership',
            'trust' => 'Trust',
            'llc' => 'LLC',
            'quickbooksssn' => 'QuickBooks SSN',
            'quickbooksein' => 'QuickBooks EIN',
        ];
    }

    public function createPayablePayment()
    {
        return AccountingPayablePayment::create(['created_by' => getUserId(), 'created_date' => time()]);
    }

    /**
     * fees orders  apply payment
     */
    public function feeOrderApplyPayment($items)
    {
        $newPayment = $this->createPayablePayment();

        foreach ($items as $item) {
            $order = OrderAddFee::findOrFail($item);

            if (!$order) {
                continue;
            }

            if ($order) {
                $order->update(['paid' => date('Y-m-d H:i:s')]);

                $orderData = $this->feeOrderData($order, $newPayment->id);
            }
        }
        EmailNotification::create(['payable_id' => $newPayment->id, 'created_date' => time()]);
        return ['success' => true, 'message' => 'Payment Applied. '.count($items).' Orders'];
    }

    public function feeOrderData($order, $paymentId)
    {
        $userData = $order->userData ? $order->userData : null;

        $paidDate = date('Y-m-d H:i:s');

        $requestdata = Session::get('data')[0];

        $amount = 0;

        if ($userData) {
            $amount = $this->getFeeOrders($requestdata, $order->apprid)->first()->totalSplit;
            if (!$amount) {
                $amount = 0;
            }
        }

        $checkNumber =  $this->getCheckNumber($userData->user_id);

        $item = [
            'orderid' => $order->id,
            'apprid' => $order->apprid,
            'paid' => $paidDate,
            'paidby' => 'check',
            'checknum' => $checkNumber,
            'checkamount' => $amount,
            'date_sent' => date('Y-m-d H:i:s')
        ];

        $message = 'Appraiser Additional Fee Payment Applied  .<br />Check Number: '.$checkNumber.'<br />Check Amount: $'.$amount;

        $createOrderLog = $this->createOrderLog($order->id, $message);

        // Record Payment
        AppraiserPayment::create($item);

        $this->createPaymentRecord($order, $paymentId, $amount, $checkNumber, $order->apprid, $order->amount);

        return $item;
    }

    /**
     * alt sub orders  apply payment
     */
    public function altSubOrderApplyPayment($items)
    {
        $newPayment = $this->createPayablePayment();

        foreach ($items as $item) {
            $order = SubOrder::findOrFail($item);

            if (!$order) {
                continue;
            }

            if ($order) {
                $order->update(['agent_paid' => date('Y-m-d H:i:s')]);

                $orderData = $this->altSubOrderData($order, $newPayment->id);
            }
        }

        return ['success' => true, 'message' => 'Payment Applied. '.count($items).' Orders'];
    }

    public function altSubOrderData($order, $paymentId)
    {
        $agentData = $order->agentData ? $order->agentData : null;

        $paidDate = date('Y-m-d H:i:s');

        $requestdata = Session::get('data')[0];

        $amount = 0;

        if ($agentData) {
            $amount = $this->getAltSubOrders($requestdata, $order->acceptedby)->first()->totalSplit;
            if (!$amount) {
                $amount = 0;
            }
        }

        $checkNumber =  $this->getCheckNumber($agentData->user_id);

        $item = [
            'orderid' => $order->id,
            'agentid' => $agentData->user_id,
            'paid' => $paidDate,
            'paidby' => 'check',
            'checknum' => $checkNumber,
            'checkamount' => $amount,
            'date_sent' => date('Y-m-d H:i:s')
        ];

        $message = 'Agent Payment Applied.  .<br />Check Number: '.$checkNumber.'<br />Check Amount: $'.$amount;
        $this->createAltOrderLog($order->id, $message);

        // Record Payment
        AppraiserPayment::create($item);

        $this->createPaymentRecord($order, $paymentId, $amount, $checkNumber);

        return $item;
    }

    /**
     * alt orders  apply payment
     */
    public function altOrderApplyPayment($items)
    {
        $newPayment = $this->createPayablePayment();

        foreach ($items as $item) {
            $order = AltOrder::findOrFail($item);

            if (!$order) {
                continue;
            }

            if ($order) {
                $order->update(['agent_paid' => date('Y-m-d H:i:s')]);

                $orderData = $this->altOrderData($order, $newPayment->id);
            }
        }

        return ['success' => true, 'message' => 'payment_applied. '.count($items).' Orders'];
    }


    public function altOrderData($order, $paymentId)
    {
        $agentData = $order->agentData ? $order->agentData : null;

        $paidDate = date('Y-m-d H:i:s');

        $requestdata = Session::get('data')[0];

        $amount = 0;

        if ($agentData) {
            $amount = $this->getAltOrders($requestdata, $order->acceptedby)->first()->totalSplit;
            if (!$amount) {
                $amount = 0;
            }
        }

        $checkNumber =  $this->getCheckNumber($agentData->user_id);

        $item = [
            'orderid' => $order->id,
            'agentid' => $agentData->user_id,
            'paid' => $paidDate,
            'paidby' => 'check',
            'checknum' => $checkNumber,
            'checkamount' => $amount,
            'date_sent' => date('Y-m-d H:i:s')
        ];

        $message = 'Agent Payment Applied.  .<br />Check Number: '.$checkNumber.'<br />Check Amount: $'.$amount;
        $this->createAltOrderLog($order->id, $message);

        // Record Payment
        AppraiserPayment::create($item);

        $this->createPaymentRecord($order, $paymentId, $amount, $checkNumber);

        return $item;
    }

    public function createAltOrderLog($orderId, $subject)
    {
        $data = [];

        $data['orderid'] = $orderId;
        $data['userid'] = getUserId();
        $data['ticketid'] = 0;
        $data['email'] = '';
        $data['info'] = $subject;
        $data['html_content'] = '';
        $data['type_id'] = '-1';
        $data['is_client_visible'] = 0;
        $data['is_appr_visible'] = 0;
        $data['is_highlight'] =  0;

        return AltOrderLog::create($data);
    }

    /**
     * appply apprasial  payment
     */
    public function apprasialApplyPayment($items)
    {
        $newPayment = $this->createPayablePayment();

        foreach ($items as $item) {
            $order = Order::findOrFail($item);

            if (!$order) {
                continue;
            }
            if ($order) {
                $order->update(['appr_paid' => date('Y-m-d H:i:s')]);

                $orderData = $this->appraisalOrderData($order, $newPayment->id);
            }
        }
        EmailNotification::create(['payable_id' => $newPayment->id, 'created_date' => time()]);
        return ['success' => true, 'message' => 'Payment Applied. '.count($items).' Orders'];
    }

    public function appraisalOrderData($order, $paymentId)
    {
        $userData = $order->userAcceptedBy->userData ? $order->userAcceptedBy->userData : null;
        $paidDate = date('Y-m-d H:i:s');
        $requestdata = Session::get('data')[0];

        $amount = 0;
        if ($userData) {
            $amount = $this->getAppraiserTotalSplit($requestdata, $order->acceptedby);
            if (!$amount) {
                $amount = 0;
            }
        }

        $checkNumber =  $this->getCheckNumber($order->acceptedby);

        $item = [
            'orderid' => $order->id,
            'apprid' => $order->acceptedby,
            'paid' => $paidDate,
            'paidby' => 'check',
            'checknum' => $checkNumber,
            'checkamount' => $amount,
            'date_sent' => date('Y-m-d H:i:s')
        ];

        $userName = $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A';

        $message = 'Appraiser Payment  .<br />Check Number: '.$checkNumber.'<br />Check Amount: $'.$amount.'<br />Appraiser User ID:'.$order->acceptedby.'<br />Appraiser Name: ' .$userName;
        $createOrderLog = $this->createOrderLog($order->id, $message);

        // Record Payment
        AppraiserPayment::create($item);

        $this->createPaymentRecord($order, $paymentId, $amount, $checkNumber);

        return $item;
    }

    public function createPaymentRecord($order, $paymentId, $amount, $checkNumber, $apprId = null, $splitAmount = null)
    {
        $data = [];

        $userData = $order->userAcceptedBy->userData ? $order->userAcceptedBy->userData : null;

        if ($userData->payable_company) {
            $address =  $userData->payable_address ? trim($userData->payable_address . ' ' . $userData->payable_address1) : null;
        } else {
            $address =  $userData->comp_address;
        }

        if ($userData->payable_company) {
            $city = $userData->payable_city;
        } else {
            $city = $userData->comp_city;
        }

        if ($userData->payable_company) {
            $state =  $userData->payable_state;
        } else {
            $state = $userData->comp_state;
        }

        if ($userData->payable_company) {
            $zip =  $userData->payable_zip;
        } else {
            $zip =  $userData->comp_zip;
        }

        $data['payable_payment_id'] = $paymentId;
        $data['uid'] = $apprId ? $apprId : $order->acceptedby;
        $data['name'] = $userData ? $userData->firstname. ' '. $userData->lastname : 'N/A';
        $data['address'] = $address;
        $data['city'] = $city;
        $data['state'] = $state;
        $data['zip'] = $zip;
        $data['check_number'] = $checkNumber;
        $data['check_amount'] = $amount;
        $data['pay_date'] = date('Y-m-d H:i:s');
        $data['date_delivered'] = $order->date_delivered ? $order->date_delivered : '0000-00-00';
        $data['orderid'] = $order->id;
        $data['prop_address'] = $order->propaddress1 ? $order->propaddress1 : 'N/A';
        $data['split'] = $splitAmount ? $splitAmount : $order->split_amount ;

        return AccountingPayablePaymentRecord::create($data);
    }

    public function createOrderLog($orderId, $subject)
    {
        $data = [];

        $data['orderid'] = $orderId;
        $data['userid'] = getUserId();
        $data['ticketid'] = 0;
        $data['email'] = '';
        $data['info'] = $subject;
        $data['html_content'] = '';
        $data['type_id'] = 1;
        $data['is_client_visible'] = 0;
        $data['is_appr_visible'] = 0;
        $data['is_highlight'] =  0;

        return OrderLog::create($data);
    }


    /**
     * @param $id
     * @return string
     */
    public function downloadDocument(UserDoc $document)
    {
        return $this->createS3Storage->downloadFile($document->fullPath);
    }
}
