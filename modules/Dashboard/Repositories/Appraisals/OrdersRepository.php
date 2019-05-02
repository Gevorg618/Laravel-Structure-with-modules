<?php

namespace Dashboard\Repositories\Appraisals;

use DB;
use Dashboard\Models\Appraisals\Order;

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

    public function companyOrders($filters)
    {
      $query = $this->query($filters);

      if(false && user()->isGroupSupervisor) {
        $query->ofGroupSupervisor(user()->id);
      } elseif (user()->isGroupManager) {
        $query->ofGroupManager(user()->id);
      } else {
        $query->ofPlacedBy(user()->id);
      }

      $rows = $query->paginate($filters['perPage'] ?? null);

      return $rows;
    }

    protected function query($filters)
    {
      $query = $this->order
        ->with(['orderStatus' => function($query) {
          $query->select(['id', DB::raw('descrip as title')]);
        }, 'apprType' => function($query) {
          $query->select(['id', 'form', 'descrip', 'short_descrip']);
        }])
        ->select(['id', 'loanrefnum', 'appr_type', 'schd_date', 
                  'propaddress1', 'propaddress2', 'propcity', 'propstate', 'propzip', 
                  'borrower', 'status', 'ordereddate', 'is_cod', 'billmelater', 
                  'is_check_payment', 'paid_amount', 'invoicedue', 'is_collect_from_borrower',
                  'refund_date', 'is_order_paid']);

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
