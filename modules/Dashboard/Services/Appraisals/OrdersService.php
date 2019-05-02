<?php

namespace Dashboard\Services\Appraisals;

use Dashboard\Repositories\Appraisals\OrdersRepository;

class OrdersService
{
    /**
     * @var OrdersRepository
     */
    protected $orderRepo;

    /**
     * AccountsPayableService constructor.
     * @param $orderRepo
     */
    public function __construct(OrdersRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function orders($filters)
    {
      return $this->orderRepo->orders($filters);
    }

    public function companyOrders($filters)
    {
      return $this->orderRepo->companyOrders($filters);
    }
}