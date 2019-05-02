<?php

namespace Modules\Admin\Repositories\Statistics;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Yajra\DataTables\Datatables;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Calendar;

class StatisticsRepository
{

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
     * StatisticsRepository constructor.
     *
     */
    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->typeRepo = new TypesRepository();
    }

    /**
     * create calnedar
     * 
     * @return object $calendar 
     */
    public function calendar()
    {
       
        $events = $this->calendarEvents();

        $calendar = \Calendar::addEvents($events)->setOptions([ //set fullcalendar options
                'allDay' => false,
                'displayEventTime' => false
        ])->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
            'events' => "function(start, end, timezone, callback) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/statistics-user-tracking/statistics/calendar-data',
                    data: {start_date:start.unix(), end_date:end.unix()},
                    beforeSend: function () {
                    },
                    success: function(data) {
                        var events = [];
                        
                        $(data).each(function(index, element) {
                          events.push({
                            title: element.title,
                            start: element.start.date, // will be parsed
                            end: element.end.date,
                            allDay: false,
                            displayEventTime: false
                          });
                        });
                        
                        callback(events);
                        $('.fc-time').css('display', 'none');
                    }
                });
            }",
            'loading' => "function( isLoading, view ) {
                
                if(isLoading) {// isLoading gives boolean value
                    //show your loader here 
                } else {
                     $('.fc-time').css('display', 'none');
                }
            }"
        ]);
        
        return $calendar;
    }

    /**
     * get calendar data
     * 
     * @return object $calendar 
     */
    public function calendarEvents($fromDate = null , $toDate = null )
    {
        if (!$fromDate || !$toDate) {
            $fromDate = sprintf("%s 00:00:00", date('Y-m-d'));
            $toDate = sprintf("%s 23:59:59", date('Y-m-d'));
        }
        
        
        $events = [];

        $items = [];

        // get created orders
        $createdOrders = $this->orderRepo->getStatsCreatedOrders($fromDate, $toDate)->get();

        foreach ($createdOrders as $order) {

            // Get date
            $date = date('Y-m-d 00:00:00', strtotime($order->ordereddate));
            
            if(isset($items[$date]['created'])) {
            
                $items[$date]['created']++;
            
            } else {
            
                $items[$date]['created'] = 1;
            
            }
        }
        
        // get completed orders
        $completedOrders = $this->orderRepo->getStatsCompletedOrders($fromDate, $toDate)->get();

        foreach($completedOrders as $order) {

            // Get date
            $date = date('Y-m-d 00:00:01', strtotime($order->date_delivered));

            if(isset($items[$date]['completed'])) {
                $items[$date]['completed']++;
            } else {
                $items[$date]['completed'] = 1;
            }
        }


        foreach($items as $date => $values) {

            if(isset($values['created'])) {

                $events[] = \Calendar::event(
                                sprintf("%s Orders Placed", number_format($values['created'])), //event title
                                true, //full day event?
                                $date, //start time (you can also use Carbon instead of DateTime)
                                $date, //end time (you can also use Carbon instead of DateTime)
                                0
                            );
            }
            
            if (isset($values['completed'])) {

                $events[] = \Calendar::event(
                                sprintf("%s Orders Completed", number_format($values['completed'])), //event title
                                true, //full day event?
                                $date, //start time (you can also use Carbon instead of DateTime)
                                $date, //end time (you can also use Carbon instead of DateTime)
                                0
                            );
            }
        }

        return $events;
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
    public function statistics($fromDate, $toDate, $clients = [], $type = null, $skip = null, $take = null)
    {
       
       $response = false;

       switch ($type) {
            case 'placed':
                    $response = $this->placedOrdersDataTables($fromDate, $toDate, $clients, $skip , $take);
               break;
            case 'assigned':
                    $response = $this->assignedOrdersDataTables($fromDate, $toDate, $clients, $skip , $take);
               break;   
            case 'low_margin':
                    $response = $this->lowMarginOrdersDataTables($fromDate, $toDate, $clients, $skip , $take);
               break;
            case 'completed':
                    $response = $this->completedOrdersDataTables($fromDate, $toDate, $clients, $skip , $take);
               break;
            case 'canceled':
                    $response = $this->canceledOrdersDataTables($fromDate, $toDate, $clients, $skip , $take);
               break;
            case 'charts':
                    $response['chartsData'] = $this->getChartsDataByApprTypes($fromDate, $toDate, $clients);
               break;
       }

       return $response;
    }

    /**
     * get completed orders
     *
     * @return array collection
     */
    public function getChartsDataByApprTypes($fromDate, $toDate, $clients)
    {
        $types = $this->typeRepo->createTypesArray();
        $chartsData = [];

        $chartsDataCompleted = [];
        
        $completedOrdersGroupTypes = $this->getCompletedOrders($fromDate, $toDate, $clients)->get()->groupBy('appr_type')->toArray();
        $createdOrdersGroupTypes = $this->getCreatedOrders($fromDate, $toDate, $clients)->get()->groupBy('appr_type')->toArray();
    
        foreach ($types as $typeId => $typeName) {

            $completedCount = 0; 
            $createdCount = 0;

            if (array_key_exists($typeId, $completedOrdersGroupTypes)) {
                $completedCount = count($completedOrdersGroupTypes[$typeId]);
            }

            if (array_key_exists($typeId, $createdOrdersGroupTypes)) {
                $createdCount = count($createdOrdersGroupTypes[$typeId]);
            }

            if (!array_key_exists($typeId, $createdOrdersGroupTypes) && !array_key_exists($typeId, $completedOrdersGroupTypes)) {
                continue;
            }            

            $chartsData[$typeName] =  [ 'completed' => $createdCount, 'created' => $completedCount];
        }
        
        return $chartsData;

    }

    /**
     * get completed orders
     *
     * @return array collection
     */
    public function getCompletedOrders($fromDate, $toDate, $clients)
    {

        $completedOrders = $this->orderRepo->getStatsCreatedOrders($fromDate, $toDate);

        if ($clients) {

            $completedOrders = $completedOrders->whereHas('groupData' , function($query) use ($clients) {
                $query->whereIn('id', $clients);
            });
        }

        return $completedOrders;
    }

    /**
     * get created orders
     *
     * @return array collection
     */
    public function getCreatedOrders($fromDate, $toDate, $clients)
    {

        $placedOrders = $this->orderRepo->getStatsCreatedOrders($fromDate, $toDate);

        if ($clients) {

            $placedOrders = $placedOrders->whereHas('groupData' , function($query) use ($clients) {
                $query->whereIn('id', $clients);
            });
        }

        return $placedOrders;
    }

    /**
     * get place orders for dataTable
     *
     * @return array $placedOrdersDataTables
     */
    public function placedOrdersDataTables($fromDate, $toDate, $clients, $skip , $take)
    {

        $placedOrders = $this->getCreatedOrders($fromDate, $toDate, $clients);
        $placedRawCount = $placedOrders->count();
       
        $placedOrders = $placedOrders->skip((int)$skip)->take((int)$take);
      
        $placedOrdersDataTables = Datatables::of($placedOrders)
                ->editColumn('orderedate', function ($placedOrder) {
                    return $placedOrder->ordereddate;
                })
                ->editColumn('company', function ($placedOrder) {
                    return $placedOrder->groupData ? $placedOrder->groupData->descrip : 'N/A';
                })
                ->editColumn('user', function ($placedOrder) {
                    return $placedOrder->userData ? $placedOrder->userData->firstname.' '.$placedOrder->userData->lastname: '';
                })
                ->editColumn('appr_type', function ($placedOrder) {
                    return $placedOrder->appraisalType ? $placedOrder->appraisalType->form . ' ' . $placedOrder->appraisalType->descrip : 'N/A';
                })
                ->editColumn('address', function ($placedOrder) {
                    return $placedOrder->propaddress1;
                })
                ->editColumn('state', function ($placedOrder) {
                    return $placedOrder->propstate;
                })
                ->editColumn('payment_status', function ($placedOrder) {
                    return $placedOrder->getPaymentStatusAttribute();
                })
                ->editColumn('invoice_amount', function ($placedOrder) {
                    return $placedOrder->invoicedue;
                })
                ->editColumn('split_amount', function ($placedOrder) {
                    return $placedOrder->split_amount;
                })
                ->editColumn('margin', function ($placedOrder) {
                    return $placedOrder->invoicedue - $placedOrder->split_amount;
                })
                ->setTotalRecords($placedRawCount)
                ->make(true);
                
        return $placedOrdersDataTables;
    }


    /**
     * get completed orders for dataTable
     *
     * @return array $completedOrdersDataTables
     */
    public function completedOrdersDataTables($fromDate, $toDate, $clients, $skip , $take)
    {

        $completedOrders = $this->getCompletedOrders($fromDate, $toDate, $clients); 
        $completedRawCount = $completedOrders->count();
       
        $completedOrders = $completedOrders->skip((int)$skip)->take((int)$take);

        $completedOrdersDataTables = Datatables::of($completedOrders)
                ->editColumn('orderedate', function ($completedOrder) {
                    return $completedOrder->ordereddate;
                })
                ->editColumn('company', function ($completedOrder) {
                    return $completedOrder->groupData ? $completedOrder->groupData->descrip : 'N/A';
                })
                ->editColumn('user', function ($completedOrder) {
                    return $completedOrder->userData ? $completedOrder->userData->firstname.' '.$completedOrder->userData->lastname: '';
                })
                ->editColumn('appr_type', function ($completedOrder) {
                    return $completedOrder->appraisalType ? $completedOrder->appraisalType->form . ' ' . $completedOrder->appraisalType->descrip : 'N/A';
                })
                ->editColumn('address', function ($completedOrder) {
                    return $completedOrder->propaddress1;
                })
                ->editColumn('state', function ($completedOrder) {
                    return $completedOrder->propstate;
                })
                ->editColumn('payment_status', function ($completedOrder) {
                    return $completedOrder->getPaymentStatusAttribute();
                })
                ->editColumn('invoice_amount', function ($completedOrder) {
                    return $completedOrder->invoicedue;
                })
                ->editColumn('split_amount', function ($completedOrder) {
                    return $completedOrder->split_amount;
                })
                ->editColumn('margin', function ($completedOrder) {
                    return $completedOrder->invoicedue - $completedOrder->split_amount;
                })
                ->editColumn('total_turn_time', function ($completedOrder) {
                    $orderedDate = \Carbon::createFromFormat('Y-m-d H:i:s', $completedOrder->ordereddate);

                    $startDate = \Carbon::parse($orderedDate);
                    $endDate = \Carbon::parse($completedOrder->date_delivered);

                    $totalDuration = $endDate->diffInMinutes($startDate);

                    return gmdate('H:i:s', $totalDuration);
                })
                ->setTotalRecords($completedRawCount)
                ->make(true);
                
        return $completedOrdersDataTables;
    }


    /**
     * get assigned orders for dataTable
     *
     * @return array $assignedOrdersDataTables
     */
    public function assignedOrdersDataTables($fromDate, $toDate, $clients, $skip , $take)
    {

        $assignedOrders = $this->orderRepo->getStatsAssignedOrders($fromDate, $toDate);

        
        if ($clients) {

            $assignedOrders = $assignedOrders->whereHas('groupData' , function($query) use ($clients) {
                $query->whereIn('id', $clients);
            });
        }

        $assignedRawCount = $assignedOrders->count();

        $assignedOrders = $assignedOrders->skip((int)$skip)->take((int)$take);
        
        $assignedOrdersDataTables = Datatables::of($assignedOrders)
                ->editColumn('orderedate', function ($assignedOrder) {
                    return $assignedOrder->ordereddate;
                })
                ->editColumn('company', function ($assignedOrder) {
                    return $assignedOrder->groupData ? $assignedOrder->groupData->descrip : 'N/A';
                })
                ->editColumn('appr_type', function ($assignedOrder) {
                    return $assignedOrder->appraisalType ? $assignedOrder->appraisalType->form . ' ' . $assignedOrder->appraisalType->descrip : 'N/A';
                })
                ->editColumn('address', function ($assignedOrder) {
                    return $assignedOrder->propaddress1;
                })
                ->editColumn('state', function ($assignedOrder) {
                    return $assignedOrder->propstate;
                })
                ->editColumn('payment_status', function ($assignedOrder) {
                    return $assignedOrder->getPaymentStatusAttribute();
                })
                ->editColumn('invoice_amount', function ($assignedOrder) {
                    return $assignedOrder->invoicedue;
                })
                ->editColumn('split_amount', function ($assignedOrder) {
                    return $assignedOrder->split_amount;
                })
                ->editColumn('margin', function ($assignedOrder) {
                    return $assignedOrder->invoicedue - $assignedOrder->split_amount;
                })                
                ->editColumn('engager', function ($assignedOrder) {
                    return $assignedOrder->userDataByAssigned ? $assignedOrder->userDataByAssigned->firstname . ' ' . $assignedOrder->userDataByAssigned->lastname : 'N/A';
                })
                ->editColumn('team', function ($assignedOrder) {
                    return $assignedOrder->getTeamTitle() ? $assignedOrder->getTeamTitle() : 'N/A';
                })->setTotalRecords($assignedRawCount)
                ->make(true);
                
        return $assignedOrdersDataTables;
    }

    /**
     * get canceled orders for dataTable
     *
     * @return array $canceledOrdersDataTables
     */
    public function canceledOrdersDataTables($fromDate, $toDate, $clients, $skip , $take)
    {

        $canceledOrders = $this->orderRepo->getStatsOrdersCanceled($fromDate, $toDate);

        if ($clients) {

            $canceledOrders = $canceledOrders->whereHas('groupData' , function($query) use ($clients) {
                $query->whereIn('id', $clients);
            });
        }

        $canceledRawCount = $canceledOrders->count();

        $canceledOrders = $canceledOrders->skip((int)$skip)->take((int)$take);
        
        $canceledOrdersDataTables = Datatables::of($canceledOrders)
                ->editColumn('orderedate', function ($canceledOrder) {
                    return $canceledOrder->ordereddate;
                })
                ->editColumn('company', function ($canceledOrder) {
                    return $canceledOrder->groupData ? $canceledOrder->groupData->descrip : 'N/A';
                })
                ->editColumn('user', function ($canceledOrder) {
                    return $canceledOrder->userData ? $canceledOrder->userData->firstname.' '.$canceledOrder->userData->lastname: '';
                })
                ->editColumn('appr_type', function ($canceledOrder) {
                    return $canceledOrder->appraisalType ? $canceledOrder->appraisalType->form . ' ' . $canceledOrder->appraisalType->descrip : 'N/A';
                })
                ->editColumn('address', function ($canceledOrder) {
                    return $canceledOrder->propaddress1;
                })
                ->editColumn('state', function ($canceledOrder) {
                    return $canceledOrder->propstate;
                })
                ->editColumn('payment_status', function ($canceledOrder) {
                    return $canceledOrder->getPaymentStatusAttribute();
                })
                ->editColumn('invoice_amount', function ($canceledOrder) {
                    return $canceledOrder->invoicedue;
                })
                ->editColumn('split_amount', function ($canceledOrder) {
                    return $canceledOrder->split_amount;
                })
                ->editColumn('margin', function ($canceledOrder) {
                    return $canceledOrder->invoicedue - $canceledOrder->split_amount;
                })
                ->editColumn('team', function ($canceledOrder) {
                    return $canceledOrder->getTeamTitle() ? $canceledOrder->getTeamTitle() : 'N/A';
                })->setTotalRecords($canceledRawCount)
                ->make(true);
                
        return $canceledOrdersDataTables;
    }

    /**
     * get low margin orders for dataTable
     *
     * @return array $lowMarginOrdersDataTables
     */
    public function lowMarginOrdersDataTables($fromDate, $toDate, $clients, $skip , $take)
    {

        $lowMarginOrders = $this->orderRepo->getStatsLowMarginOrders($fromDate, $toDate);

        if ($clients) {

            $lowMarginOrders = $lowMarginOrders->whereHas('groupData' , function($query) use ($clients) {
                $query->whereIn('id', $clients);
            });
        }
        
        $lowMarginRawCount = $lowMarginOrders->get()->count();

        $lowMarginOrders = $lowMarginOrders->skip((int)$skip)->take((int)$take);

        
        $lowMarginOrdersDataTables = Datatables::of($lowMarginOrders)
                ->editColumn('orderedate', function ($lowMarginOrder) {
                    return $lowMarginOrder->ordereddate;
                })
                ->editColumn('company', function ($lowMarginOrder) {
                    return $lowMarginOrder->groupData ? $lowMarginOrder->groupData->descrip : 'N/A';
                })
                ->editColumn('user', function ($lowMarginOrder) {
                    return $lowMarginOrder->userData ? $lowMarginOrder->userData->firstname.' '.$lowMarginOrder->userData->lastname: '';
                })
                ->editColumn('appr_type', function ($lowMarginOrder) {
                    return $lowMarginOrder->appraisalType ? $lowMarginOrder->appraisalType->form . ' ' . $lowMarginOrder->appraisalType->descrip : 'N/A';
                })
                ->editColumn('address', function ($lowMarginOrder) {
                    return $lowMarginOrder->propaddress1;
                })
                ->editColumn('state', function ($lowMarginOrder) {
                    return $lowMarginOrder->propstate;
                })
                ->editColumn('payment_status', function ($lowMarginOrder) {
                    return $lowMarginOrder->getPaymentStatusAttribute();
                })
                ->editColumn('invoice_amount', function ($lowMarginOrder) {
                    return $lowMarginOrder->invoicedue;
                })
                ->editColumn('split_amount', function ($lowMarginOrder) {
                    return $lowMarginOrder->split_amount;
                })
                ->editColumn('margin', function ($lowMarginOrder) {
                    return $lowMarginOrder->invoicedue - $lowMarginOrder->split_amount;
                })
                ->editColumn('engager', function ($lowMarginOrder) {
                    return $lowMarginOrder->userDataByAssigned ? $lowMarginOrder->userDataByAssigned->firstname . ' ' . $lowMarginOrder->userDataByAssigned->lastname : 'N/A';
                })->setTotalRecords($lowMarginRawCount)
                ->make(true);
                
        return $lowMarginOrdersDataTables;
    }

}
