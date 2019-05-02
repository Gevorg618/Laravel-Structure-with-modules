<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use App\Models\Clients\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Accounting\BatchCheck\ApplyBatchCCRequest;
use Modules\Admin\Http\Requests\Accounting\BatchCheck\ApplyBatchCheckRequest;
use Modules\Admin\Http\Requests\Accounting\BatchCheck\BatchCheckShowOrders;
use Modules\Admin\Services\Accounting\Batch\BatchCheckService;

class BatchCheckController extends Controller
{
    protected $service;

    /**
     * BatchCheckController constructor.
     * @param $service
     */
    public function __construct(BatchCheckService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::accounting.batch-check.index', [
            'clients' => Client::pluck('descrip', 'id'),
        ]);
    }

    /**
     * @param BatchCheckShowOrders $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(BatchCheckShowOrders $request)
    {
        $from = $request->post('date_from');
        $to = $request->post('date_to');
        $type = $request->post('type');
        $clients = $request->post('clients');
        list($rows, $totals) = $this->service->getOrders($from, $to, $type, $clients);
        return response()->json([
            'html' => view('admin::accounting.batch-check.orders', [
                'rows' => $rows,
                'totals' => $totals,
                'checkTypes' => $this->service->getCheckTypes(),
            ])->render(),
        ]);
    }

    /**
     * @param ApplyBatchCheckRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyBatchCheck(ApplyBatchCheckRequest $request)
    {
        $result = $this->service->applyBatchCheck($request->all());
        return response()->json($result);
    }

    /**
     * @param ApplyBatchCCRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyBatchCC(ApplyBatchCCRequest $request)
    {
        $result = $this->service->applyBatchCC($request->all());
        return response()->json($result);
    }
}
