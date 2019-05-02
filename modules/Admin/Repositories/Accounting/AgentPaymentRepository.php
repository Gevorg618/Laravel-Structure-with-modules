<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\AlternativeValuation\AgentPayment;

/**
 * Class AgentPaymentRepository
 * @package Modules\Admin\Repositories
 */
class AgentPaymentRepository
{
    /**
     * @param array $orderIds
     * @param array $userIds
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrderAgentsCheckByOrdersAndAgentsId($orderIds = [], $userIds = [])
    {
        return AgentPayment::whereIn('orderid', $orderIds)
            ->whereIn('agentid', $userIds)->get();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAgentCheckPaymentsById($userId)
    {
        return AgentPayment::where('agentid', $userId)->latest('paid')->get();
    }
}