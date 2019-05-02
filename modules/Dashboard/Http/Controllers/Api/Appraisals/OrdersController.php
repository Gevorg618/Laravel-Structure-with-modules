<?php

namespace Dashboard\Http\Controllers\Api\Appraisals;

use Dashboard\Services\Appraisals\OrdersService;
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

    public function company()
    {
      // Make sure we are managers
      abort_unless(user()->isAnyManager, 403);

      $rows = $this->service->companyOrders(request()->all());
      return response()->json($rows);
    }
}
