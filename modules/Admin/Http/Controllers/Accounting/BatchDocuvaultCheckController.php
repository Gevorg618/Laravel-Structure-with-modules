<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use App\Models\Clients\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Accounting\BatchCheck\ApplyBatchDocuvaultCheck;
use Modules\Admin\Http\Requests\Accounting\BatchCheck\ApplyBatchDocuvaultCheckCC;
use Modules\Admin\Http\Requests\Accounting\BatchCheck\BatchDocuVaultCheckShowOrders;
use Modules\Admin\Services\Accounting\Batch\BatchDocuvaultService;

class BatchDocuvaultCheckController extends Controller
{
    protected $service;

    /**
     * BatchDocuvaultCheckController constructor.
     * @param $service
     */
    public function __construct(BatchDocuvaultService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::accounting.batch-docuvault-check.index', [
            'orderTypes' => $this->service->getOrderTypes(),
            'clients' => Client::pluck('descrip', 'id'),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOrders(BatchDocuVaultCheckShowOrders $request)
    {
        $dateFrom = strtotime($request->post('date_from'));
        $dateTo = strtotime($request->post('date_to'));
        $clients = $request->post('clients');
        $orderType = $request->post('ordertype');
        $orders = $this->service->getOrdersForBatch($orderType, $dateFrom, $dateTo, $clients);
        list($rows, $totals, $ids) = $this->service->setRowsAndTotals($orders, $orderType);

        return response()->json([
            'html' => view('admin::accounting.batch-docuvault-check.partials.orders', [
                'rows' => $rows,
                'totals' => $totals,
                'orderType' => $orderType,
                'types' => $this->service->getOrderTypes(),
                'states' => getStates(),
            ])->render(),
            'ids' => $ids,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyBatchCheck(ApplyBatchDocuvaultCheck $request)
    {
        $data = [
            $request->post('ids'),
            $request->post('ordertype'),
            $request->post('check_number'),
            $request->post('from'),
            $request->post('date'),
            $request->post('type'),
        ];

        $this->service->applyBatchCheck($data);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @param Request $request
     */
    public function applyBatchCheckCredit(ApplyBatchDocuvaultCheckCC $request)
    {
        $data = $request->all();
        $result = $this->service->applyCreditCard($data);
        return response()->json($result);
    }
}
