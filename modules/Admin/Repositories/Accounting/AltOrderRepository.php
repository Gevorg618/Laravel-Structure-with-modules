<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\AlternativeValuation\Order;
use App\Models\AlternativeValuation\OrderStatus;

/**
 * Class AltOrderRepository
 * @package Modules\Admin\Repositories
 */
class AltOrderRepository
{
    const TYPE_APPRAISAL = 1;
    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $states
     * @param array $clients
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAltOrdersForPayable($dateFrom = '', $dateTo = '', $states = [], $clients = [])
    {
        $orders = Order::with([
            'client',
            'userData' => function($query) {
                return $query->orderBy('firstname');
            },
            'groupData'
        ])->where('type_id', self::TYPE_APPRAISAL)
            ->whereNull('agent_paid')
            ->whereIn('status', [
                OrderStatus::COMPLETE,
                OrderStatus::CANCELLED_TRIP_FEE
            ]);
        if ($dateFrom && $dateTo) {
            $orders = $orders->where(\DB::raw(
                sprintf("IF(status = %s, (submitted >= '%s 00:00:00' AND submitted <= '%s 23:23:59'), submitted IS NOT NULL)", OrderStatus::COMPLETE, $dateFrom, $dateTo)
            ));
        } else {
            $orders = $orders->where(\DB::raw(
                sprintf("IF(status = %s, (submitted <= '%s 23:23:59'), submitted IS NOT NULL)", OrderStatus::COMPLETE, $dateTo)
            ));
        }
        if ($states) {
            $orders = $orders->whereIn('propstate', $states);
        }
        if ($clients) {
            $orders = $orders->whereHas('groupData', function ($query) use ($clients) {
                return $query->whereIn('user_groups.id', $clients);
            });
        }
        return $orders->orderBy('submitted')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAltMonthlyReport()
    {
        return Order::from('alt_order as a')
            ->select(\DB::raw("
                DATE_FORMAT(a.submitted, '%Y/%m') as date_completed, 
                SUM(a.invoicedue) as total_invoice, 
                SUM(a.paid_amount) as total_paid, 
                SUM(IF(a.type_id=1, a.split_amount, s.split_amount)) as total_split, 
                (SUM(a.invoicedue) - SUM(IF(a.type_id=1, a.split_amount, s.split_amount))) as margin
            "))->leftJoin(
                'alt_sub_order as s',
                'a.id',
                '=',
                's.parent_order_id'
            )->where('a.status', Order::STATUS_COMPLETE)
            ->where('a.submitted', '<>', '0000-00-00')
            ->groupBy('date_completed')
            ->orderBy('date_completed')->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findById($id)
    {
        return AltOrder::find($id);
    }

    /**
     * @param $userId
     * @return int
     */
    public function countOrdersByAcceptedBy($userId)
    {
        return AltOrder::where('acceptedby', $userId)->count();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getUserAgentOrders($userId)
    {
        return AltOrder::where('acceptedby', $userId)
            ->latest('ordereddate')->get();
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getALUserSubmittedOrdersWithLimit($userId, $limit=10)
    {
        return AltOrder::where('orderedby', $userId)
            ->orderBy('ordereddate')->paginate($limit);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCompletedOrdersByAcceptedUser($userId)
    {
        return AltOrder::where('acceptedby', $userId)
            ->where('status', AltOrder::STATUS_COMPLETE)->get();
    }
}