<?php

namespace Dashboard\Http\Controllers\Api\AVM;

use Dashboard\Services\AVM\OrdersService;
use Dashboard\Http\Controllers\DashboardBaseController;

class OrdersController extends DashboardBaseController
{
    protected $service;

    /**
     * OrdersController constructor.
     * @param OrdersService $service
     */
    public function __construct(OrdersService $service)
    {
        $this->service = $service;
    }

    public function list()
    {
        $rows = $this->service->orders(request()->all());
        return response()->json($rows);
    }
}
