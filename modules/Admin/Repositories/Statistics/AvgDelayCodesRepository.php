<?php

namespace Modules\Admin\Repositories\Statistics;

use App\Models\Customizations\DelayCode;
use App\Models\Appraisal\DelayCodes\DelayCodes;
use App\Models\Appraisal\Order;
use Carbon\Carbon;

class AvgDelayCodesRepository
{
    private $delayCodes;
    private $orderDelayCodes;
    private $order;

    /**
     * AvgDelayCodesRepository constructor.
     */
    public function __construct()
    {
        $this->delayCodes = new DelayCode();
        $this->orderDelayCodes = new DelayCodes();
        $this->order = new Order();
    }

    /**
     * get Order Delay Code Types
     * @return collection
     */
    public function getOrderDelayCodeTypes()
    {
        return $this->delayCodes->select('id', 'name')->orderBy('name', 'ASC')->get();
    }

    /**
     * get Average Delay CodeDays By Type
     * @param $typeId
     * @return number
     */
    public function getAverageDelayCodeDaysByType($typeId)
    {
        $average = 0;
        $rows = $this->orderDelayCodes::getById($typeId);
        $total = $rows ? count($rows) : 0;
        $totalDays = 0;
        if ($rows && count($rows)) {
            foreach ($rows as $row) {
                $carbonDiff = Carbon::createFromTimestamp($row->start_date)->diff(Carbon::createFromTimestamp($row->end_date));
                $diff = sprintf('0 Mo %s D %s H %s M', $carbonDiff->d, $carbonDiff->h, $carbonDiff->m);
                $days = $this->getOrderTurnTimeInMinutesByTurnTimeString($diff);
                $totalDays += $days;
            }
            $average = number_format($totalDays / $total, 3);
        }
        return $average;
    }

    /**
     * get Average Delay Code Orders Days By Type Date
     * @param $typeId, $from, $to, $type, $clients, $apprTypes
     * @return collection
     */
    public function getAverageDelayCodeOrdersDaysByTypeDate($typeId, $from, $to, $type = 'date_delivered', $clients = [], $apprTypes = [])
    {
        $query = $this->order->select('appr_order.*', 'd.start_date', 'd.end_date', 'u.company', 's.descrip', 't.team_title')
                    ->leftJoin('user_groups as u', 'appr_order.groupid', '=', 'u.id')
                    ->leftJoin('appr_order_delay_code as d', 'appr_order.id', '=', 'd.order_id')
                    ->leftJoin('admin_team_client as c', 'c.user_group_id', '=', 'appr_order.groupid' )
                    ->leftJoin('admin_teams as t', 'c.team_id', '=', 't.id')
                    ->leftJoin('order_status as s', 's.id', '=', 'appr_order.status')
                    ->where('d.type_id', $typeId)
                    ->where("appr_order.{$type}", '>=', Carbon::parse($from)->format('Y-m-d H:h:s'))
                    ->where("appr_order.{$type}", '<=', Carbon::parse($to)->format('Y-m-d H:h:s'));

        if($clients) {
            $query = $query->whereIn('appr_order.groupid', $clients);
        }
        if($apprTypes) {
            $query = $query->whereIn('appr_order.appr_type', $apprTypes);
        }
        $rows =  $query->get();
        foreach ($rows as $row) {
            $carbonDiff = Carbon::createFromTimestamp($row->start_date)->diff(Carbon::createFromTimestamp($row->end_date));
            $diff = sprintf('0 Mo %s D %s H %s M', $carbonDiff->d, $carbonDiff->h, $carbonDiff->m);
            $days = $this->getOrderTurnTimeInMinutesByTurnTimeString($diff);
            $row->total_days = number_format($days, 2);
        }
        return $rows;
    }

    /**
     * get Average Delay Code Days By Type Date
     * @param $typeId, $from, $to, $type, $clients, $apprTypes
     * @return number
     */
    public function getAverageDelayCodeDaysByTypeDate($typeId, $from, $to, $type = 'date_delivered', $clients = [], $apprTypes = [])
    {
        $query = $this->orderDelayCodes->select('appr_order_delay_code.*')
                                ->leftJoin('appr_order as a', 'appr_order_delay_code.order_id', '=', 'a.id')
                                ->where('appr_order_delay_code.type_id', $typeId)
                                ->where("a.{$type}", '>=', Carbon::parse($from)->format('Y-m-d H:h:s'))
                                ->where("a.{$type}", '<=', Carbon::parse($to)->format('Y-m-d H:h:s'));
        if($clients) {
            $query = $query->whereIn('a.groupid', $clients);
        }
        if($apprTypes) {
            $query = $query->whereIn('a.appr_type', $apprTypes);
        }
        $rows = $query->get();
        $total = $rows ? count($rows) : 0;
        $average = 0;
        $totalDays = 0;
        if ($rows && count($rows)) {
            foreach ($rows as $row) {
                $carbonDiff = Carbon::createFromTimestamp($row->start_date)->diff(Carbon::createFromTimestamp($row->end_date));
                $diff = sprintf('0 Mo %s D %s H %s M', $carbonDiff->d, $carbonDiff->h, $carbonDiff->m);
                $days = $this->getOrderTurnTimeInMinutesByTurnTimeString($diff);
                $totalDays += $days;
            }
            $average = number_format($totalDays / $total, 3);
        }
        return $average;
    }

    /**
     * get Order Turn Time In Minutes By Turn Time String
     * @param $time
     * @return number
     */
    private function getOrderTurnTimeInMinutesByTurnTimeString($time)
    {
        if (!$time) {
            return 0;
        }
        preg_match('/(-?\d+) Mo (-?\d+) D (-?\d+) H (-?\d+) M/', $time, $matches);
        if (!count($matches) == 5) {
            return 0;
        }
        $months = $matches[1];
        $days = $matches[2];
        $hours = $matches[3];
        $minutes = $matches[4];
        $total = $days;
        if ($months) {
            $total += ($months * 31);
        }
        if ($hours) {
            $total += ($hours / 24);
        }
        if ($minutes) {
            $total += ($minutes / 24 / 60);
        }
        return number_format($total, 3);
    }
}
