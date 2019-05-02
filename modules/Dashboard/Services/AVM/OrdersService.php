<?php

namespace Dashboard\Services\AVM;

use Dashboard\Repositories\AVM\OrdersRepository;

class OrdersService
{
    /**
     * @var OrdersRepository
     */
    protected $orderRepo;

    /**
     *  constructor.
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
}