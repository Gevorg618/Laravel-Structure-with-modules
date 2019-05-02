<?php

namespace Dashboard\Services\Tickets;

use Dashboard\Repositories\Tickets\TicketsRepository;

class TicketsService
{
    /**
     * @var TicketsRepository
     */
    protected $repo;

    /**
     * constructor.
     * @param $orderRepo
     */
    public function __construct(TicketsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function tickets($filters)
    {
      return $this->repo->tickets($filters);
    }
}