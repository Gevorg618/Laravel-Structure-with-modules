<?php

namespace Modules\Admin\Services\Accounting;


use Modules\Admin\Repositories\Ticket\OrderRepository;

/**
 * Class PayableReportService
 * @package Modules\Admin\Services
 */
class PayableReportService
{
    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    /**
     * PayableReportService constructor.
     * @param $orderRepo
     */
    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * @param $from
     * @param $to
     * @param array $clients
     * @param array $states
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getData($from, $to, $clients = [], $states = [])
    {
        return $this->orderRepo->finalizeAccountsPayableQuery(
            $this->orderRepo->buildAccountsPayableQuery(),
            $from,
            $to,
            $clients, $states
        );
    }
}