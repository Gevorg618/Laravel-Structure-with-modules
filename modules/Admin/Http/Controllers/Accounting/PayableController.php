<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use App\Models\Accounting\AccountingPayablePayment;
use App\Models\Accounting\AccountingPayablePaymentRecord;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Repositories\Accounting\AccountingRepository;

class PayableController extends Controller
{
    protected $accountingRepo;

    /**
     * PayableController constructor.
     * @param $accountingRepo
     */
    public function __construct(AccountingRepository $accountingRepo)
    {
        $this->accountingRepo = $accountingRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::accounting.payables.index', [
            'payments' => $this->accountingRepo->getPaginatedPayablePayments(),
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(AccountingPayablePayment $payment)
    {
        $records = AccountingPayablePaymentRecord::getByPaymentId($payment->id)->get();
        return view('admin::accounting.payables.show', [
            'payment' => $payment,
            'records' => $records,
        ]);
    }
}
