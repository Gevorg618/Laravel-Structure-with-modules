<?php

namespace Modules\Admin\Repositories\Accounting;

use App\Models\Accounting\AccountingPayablePaymentRecord;
use Yajra\DataTables\Datatables;
use App\Models\Accounting\AccountingPayablePayment;

class PayableRevertRepository
{
    private $paymentRecord;

    /**
     * PayableRevertRepository constructor.
     */
    public function __construct()
    {
        $this->paymentRecord = new AccountingPayablePaymentRecord();
    }

    /**
     * 
     * @return array
     */
    public function revert($payId, $recordsId)
    {

        $records = $this->paymentRecord->whereIn('id', $recordsId);

        if ($records->count()) {
            foreach ($records->get() as $record) {
                $record->appraiserPayment()->delete();
                $order = $record->apprOrder;
                $split = $order->split_amount  - $record->check_amount;
                $record->apprOrder()->update(['split_amount', $split]);
            }
            $records->delete();
        } else {
            return ['success' => false, 'message' => 'There is no records for revert!'];
        }
        
        $payableCount = $this->paymentRecord->where('payable_payment_id', $payId)->count(); 

        if ($payableCount == 0) {
            AccountingPayablePayment::where('id', $payId);
        }
        
        return ['success' => true, 'message' => 'Revert was successfully finished!'];
    }

    /**
     * 
     * @return array
     */
    public function revertDataTables($id)
    {

        $records = $this->paymentRecord->where('payable_payment_id', $id);

        return  Datatables::of($records)
                ->editColumn('checkbox', function ($record) {
                    return view('admin::accounting.accounting-payable-revert.partials._checkbox', compact('record'))->render();
                })
                ->editColumn('name', function ($record) {
                    return $record->name;
                })
                ->editColumn('address', function ($record) {
                    return $record->address;
                })
                ->editColumn('city', function ($record) {
                    return $record->city;
                })
                ->editColumn('state', function ($record) {
                        return  $record->state;
                })
                ->editColumn('zip', function ($record) {
                        return  $record->zip;
                })
                ->editColumn('check_number', function ($record) {
                        return $record->check_number;
                })
                ->editColumn('check_amount', function ($record) {
                        return $record->check_amount;
                })
                ->editColumn('pay_date', function ($record) {
                        return $record->pay_date;
                })
                ->editColumn('orderid', function ($record) {
                        return $record->orderid;
                })
                ->editColumn('split', function ($record) {
                        return $record->split;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
    }
}