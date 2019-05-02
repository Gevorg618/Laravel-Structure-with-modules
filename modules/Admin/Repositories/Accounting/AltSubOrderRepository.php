<?php

namespace Modules\Admin\Repositories\Accounting;

use App\Models\Appraisal\AltOrder;
use App\Models\Appraisal\AltOrderStatus;
use App\Models\Appraisal\AltSubOrder;


class AltSubOrderRepository
{
    const TYPE_AVM = 2;
    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $states
     * @param array $clients
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAltSubOrdersForPayable($dateFrom = '', $dateTo = '', $states = [], $clients = [])
    {
        $subs = SubOrder::from('alt_sub_order as s')
            ->select(\DB::raw("
                a.*, 
                s.acceptedby as agent_accepted, 
                s.split_amount as split_amount, 
                agent.firstname, 
                agent.lastname, 
                agent.company, 
                agent.ein
            "))->leftJoin(
                'alt_order as a',
                's.parent_order_id',
                '=',
                'a.id'
            )->leftJoin(
                'user as u',
                'u.id',
                '=',
                'a.orderedby'
            )->leftJoin(
                'user_data as agent',
                'agent.user_id',
                '=',
                's.acceptedby'
            )->leftJoin(
                'user_groups as g',
                'g.id',
                '=',
                'u.groupid'
            )->where('a.type_id', self::TYPE_AVM)
            ->whereNull('a.agent_paid')
            ->whereIn('a.status', [
                OrderStatus::COMPLETE,
                OrderStatus::CANCELLED_TRIP_FEE
            ]);
        if ($dateFrom && $dateTo) {
            $subs = $subs->where(\DB::raw(
                sprintf("IF(a.status = %s, (a.submitted >= '%s 00:00:00' AND a.submitted <= '%s 23:23:59'), a.submitted IS NOT NULL)", OrderStatus::COMPLETE, $dateFrom, $dateTo)
            ));
        } else {
            $subs = $subs->where(\DB::raw(
                sprintf("IF(a.status = %s, (a.submitted <= '%s 23:23:59'), a.submitted IS NOT NULL)", OrderStatus::COMPLETE, $dateTo)
            ));
        }
        if ($states) {
            $subs = $subs->whereIn('a.propstate', $states);
        }
        if ($clients) {
            $subs = $subs->whereIn('g.id', $clients);
        }
        return $subs->orderBy('agent.firstname')
            ->orderBy('a.submitted')->get();
    }

    /**
     * @param $id
     * @param $agentId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getAgentByIdSubOrder($id, $agentId)
    {
        return AltSubOrder::where('parent_order_id', $id)
            ->where('acceptedby', $agentId)->first();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function countOrdersByAcceptedBy($userId)
    {
        return AltSubOrder::select(\DB::raw(
            "COUNT(DISTINCT parent_order_id) as total"
        ))->where('acceptedby', $userId)->first()->total;
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getUserAgentSubOrders($userId)
    {
        return AltSubOrder::where('acceptedby', $userId)
            ->latest('ordereddate')->get();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCompletedSubOrdersByAcceptedUser($userId)
    {
        return AltSubOrder::where('acceptedby', $userId)
            ->where('status', AltOrder::STATUS_COMPLETE)->get();
    }
}
