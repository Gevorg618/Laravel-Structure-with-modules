<?php

namespace Modules\Admin\Repositories\Statistics;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Calendar;
use Modules\Admin\Repositories\Management\AdminTeamsManager\AdminTeamClientRepository;

class BigStatisticsRepository
{   
    private $settingKey = 'stats_settings_appr_types';

    /**
     * the team type name
     * 
     * @var string
     */
    private $teamType = 'appr';

    /**
     * Object of OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;

    /**
     * Object of TypesRepository class
     *
     * @var typeRepo
     */
    private $typeRepo;

    /**
     * Object of AdminTeamClientRepository class
     *
     * @var adminTeamClientRepo
     */
    private $adminTeamClientRepo;

    /**
     * StatisticsRepository constructor.
     *
     */
    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->typeRepo = new TypesRepository();
        $this->adminTeamClientRepo = new AdminTeamClientRepository();
    }

    /**
     * get  statistics more info
     *
     * @param date $fromDate
     * @param date $toDate
     * @param array $clients
     * 
     * @return response 
     */
    public function statistics($date)
    {

       $fromDate = sprintf("%s 00:00:00", $date);
       $toDate = sprintf("%s 23:59:59", $date);

       $placedOrders  =   $this->getCreatedOrders($fromDate, $toDate);
       $completedOrders = $this->getCompletedOrders($fromDate, $toDate);
       $assignedOrders = $this->getAssignedOrders($fromDate, $toDate);
       $canceledOrders = $this->getCanceledOrders($fromDate, $toDate);
       $lineChartsData = $this->lineChartsData();
       $barChartsData = $this->barChartsData($placedOrders, $completedOrders, $assignedOrders, $canceledOrders);
       
       $response = [
            'placedOrder' => $placedOrders->count(),
            'completedOrder' => $completedOrders->count(),
            'assignedOrders' => $assignedOrders->count(),
            'canceledOrders' => $canceledOrders->count(),
            'lineChartsData' => $lineChartsData,
            'barChartsData' => $barChartsData
       ];

       return $response;
    }

    /**
     * get created orders
     *
     * @return array collection
     */
    public function getCreatedOrders($fromDate, $toDate)
    {

        $createdOrders = $this->orderRepo->getStatsCreatedOrders($fromDate, $toDate, $this->settingKey);

        return $createdOrders->get();
    }

    /**
     * get completed orders
     *
     * @return array collection
     */
    public function getCompletedOrders($fromDate, $toDate)
    {

        $completedOrders = $this->orderRepo->getStatsCompletedOrders($fromDate, $toDate, $this->settingKey);

        return $completedOrders->get();
    }

    /**
     * get assigned orders
     *
     * @return array collection
     */
    public function getAssignedOrders($fromDate, $toDate)
    {

        $assignedOrders = $this->orderRepo->getStatsAssignedOrders($fromDate, $toDate, $this->settingKey);

        return $assignedOrders->get();
    }

    /**
     * get canceled orders
     *
     * @return array collection
     */
    public function getCanceledOrders($fromDate, $toDate)
    {

        $canceledOrders = $this->orderRepo->getStatsOrdersCanceled($fromDate, $toDate, $this->settingKey);

        return $canceledOrders->get();
    }


    /**
     * get line chards data
     *
     * @return array collection
     */
    public function barChartsData($placedOrders, $completedOrders, $assignedOrders, $canceledOrders)
    {

        $teams = $this->adminTeamClientRepo->getAdminClientsByType($this->teamType);

        $teamData = [];

        foreach ($teams as $team) {

            $teamData[$team->adminTeam->team_title] = [
                'placed' => $placedOrders->where('groupid', $team->user_group_id)->count(),
                'assigned' => $assignedOrders->where('groupid', $team->user_group_id)->count(),
                'completed' => $completedOrders->where('groupid', $team->user_group_id)->count(),
                'canceled' => $canceledOrders->where('groupid', $team->user_group_id)->count(),
            ];
        }

        $jsCode = [];

        foreach($teamData as $d => $v) {
            $jsCode[] = [
                'teamName' => $d, 
                'a'        => $v['placed'], 
                'b'        => $v['assigned'], 
                'c'        => $v['completed'], 
                'd'        => $v['canceled'],
            ];
    
        }

        return $jsCode;

    }

    /**
     * get line chards data
     *
     * @return array collection
     */
    public function lineChartsData()
    {
        $numMonths = 8;

        $months = array();

        for ($i=1; $i <= $numMonths; $i++) {

            $time = strtotime(sprintf('-%s month', $i));

            $months[] = array(
                'from' => date('Y-m-d 00:00:00', strtotime('first day of this month', $time)),
                'to' => date('Y-m-d 23:59:59', strtotime('last day of this month', $time)),
                'date' => date('Y-m', $time),
            );
        }

        $months = array_reverse($months);

        $data = array();

        foreach($months as $m) {

            $monthFrom = $m['from'];
            $monthTo = $m['to'];

            // Convert to unix
            $monthFromUnix = strtotime($monthFrom);
            $monthToUnix = strtotime($monthTo);

            $placed =  $this->getCreatedOrders($monthFrom, $monthTo);
            $assigned =  $this->getCompletedOrders($monthFrom, $monthTo);
            $completed =  $this->getAssignedOrders($monthFrom, $monthTo);
            $canceled =  $this->getCanceledOrders($monthFrom, $monthTo);


            $placedCount = $placed ? $placed->count() : 0;
            $assignedCount = $assigned ? $assigned->count() : 0;
            $completedCount = $completed ? $completed->count() : 0;
            $canceledCount = $canceled ? $canceled->count() : 0;


            $data[ $m['date'] ] = array(
                'placed' => $placedCount,
                'assigned' => $assignedCount,
                'completed' => $completedCount,
                'canceled' => $canceledCount,
            );
        }

        $jsCode = array();

        foreach($data as $d => $v) {
            $jsCode[] = [
                'datetime' => $d, 
                'a'        => $v['placed'], 
                'b'        => $v['assigned'], 
                'c'        => $v['completed'], 
                'd'        => $v['canceled'],
            ];
    
        }

        return $jsCode;
    }
}
