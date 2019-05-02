<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Accounting\PayableRevertRepository;

class AccountPayableRevertController extends AdminBaseController
{
    /**
     * Object of PayableRevertRepository class
     *
     * @var payableRevertRepo
     */
    private $payableRevertRepo;
    
    /**
     * Create a new instance of AccountPayableRevertController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->payableRevertRepo = new PayableRevertRepository();
    }

    /**
     * Index page for Accounting Payable Revert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.accounting-payable-revert.index');
    }

    /**
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $payments
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            return  $this->payableRevertRepo->revertDataTables($request->get('payment_id'));
        }
    }

    /**
     * revert records
     *
     * @param Request $request
     *
     * @return view
     */
    public function revert(Request $request)
    {
        if ($request->ajax()) {
            $payId = $request->get('id');
            $recordsId = $request->get('records');
            return  $this->payableRevertRepo->revert($payId, $recordsId);
        }
    }
    
}
