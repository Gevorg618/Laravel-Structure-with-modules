<?php

namespace Dashboard\Repositories\AVM;

use DB;
use Dashboard\Models\AVM\Order;

class OrdersRepository
{
    private $order;

    /**
     * OrderRepository constructor.
     */
    public function __construct()
    {
        $this->order = new Order;
    }

    public function orders($filters)
    {
      $query = $this->query($filters)->ofPlacedBy(user()->id);
      $rows = $query->paginate($filters['perPage'] ?? null);

      return $rows;
    }

    protected function query($filters)
    {
      $query = $this->order
        ->with(['orderStatus' => function($query) {
          $query->select(['id', DB::raw('descrip as title')]);
        }])
        ->select(['id', 'loanrefnum', 
                  'propaddress1', 'propaddress2', 'propcity', 'propstate', 'propzip', 
                  'borrower', 'status', 'ordereddate', 'product', DB::raw('product as appr_type')]);

        if(isset($filters['term']) && trim($filters['term'])) {
          $query->ofSearch($filters['term']);
        }

        $query->ofStatusFilter($filters['filter'] ?? null);

      $orderBy = $filters['sortField'] ?? 'ordereddate';
      $sortBy = $filters['sortOrder'] ?? 'desc';

      $query->orderBy($orderBy, $sortBy);

      return $query;
    }
}
