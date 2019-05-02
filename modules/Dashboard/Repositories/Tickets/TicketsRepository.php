<?php

namespace Dashboard\Repositories\Tickets;

use DB;
use Dashboard\Models\Tickets\Ticket;

class TicketsRepository
{
    private $ticket;

    /**
     * TicketsRepository constructor.
     */
    public function __construct()
    {
        $this->ticket = new Ticket;
    }

    public function tickets($filters)
    {
      $query = $this->query($filters);
      $rows = $query->paginate($filters['perPage'] ?? null);

      return $rows;
    }

    protected function query($filters)
    {
        $query = $this->ticket
                      ->select(['id', 'subject', 'userid', 'created_date', 'from_content', 'to_content', 'orderid'])
                      ->withCount(['publicComments']);

        if(isset($filters['term']) && trim($filters['term'])) {
          $query->ofSearch($filters['term']);
        }

        $query->OfClosed($filters['filter'] ?? false);

        $orderBy = $filters['sortField'] ?? 'created_date';
        $sortBy = $filters['sortOrder'] ?? 'desc';

        $query->orderBy($orderBy, $sortBy);

        return $query;
    }
}
