<?php

namespace Modules\Admin\Repositories\ManagerReport;

use App\Models\ManagerReports\ReportTask;
use Yajra\DataTables\Datatables;

class TaskRepository
{   
    
	/**
     * Current timestamp
     *
     * @access	protected
     * @var		integer
     */
    protected $time_now  = 0;

    /**
     * Date pieces
     *
     * @access	protected
     * @var		array
     */
    public $date_now = [];
    
    /**
     * Constructer
     *
     * @access	public
     * @return	@e void
     */
    public function __construct()
    {
        
        /* Setup Timestamps */
        $this->time_now                = time();
        $this->date_now['minute']      = intval(date('i', $this->time_now));
        $this->date_now['hour']        = intval(date('H', $this->time_now));
        $this->date_now['wday']        = intval(date('w', $this->time_now));
        $this->date_now['mday']        = intval(date('d', $this->time_now));
        $this->date_now['month']       = intval(date('m', $this->time_now));
        $this->date_now['year']        = intval(date('Y', $this->time_now));
    }

    /**
     * get Task
     * @return array
     */
    public  function getTask($id) 
    {
       return ReportTask::find($id);
    }

    /**
     * create Task
     * @return array
     */
    public  function create($data) 
    {
       return ReportTask::insertGetId($data);
    }

    /**
     * remove Task
     * @return array
     */
    public  function remove($id) 
    {
       return ReportTask::findOrFail($id)->delete();	
    }


    /**
     * update Task
     * @return array
     */
    public  function update($id, $data) 
    {
       return ReportTask::where('id', $id)->update($data);
    }

    /**
     * Generate the next run timestamp
     *
     * @access	public
     * @param	array 		Task data
     * @return	int			Next run timestamp
     */
    public function generateNextRun($task)
    {
        //-----------------------------------------
        // Did we set a day?
        //-----------------------------------------

        $day_set       = 1;
        $min_set       = 1;
        $day_increment = 0;
        
        $this->run_day    = $this->date_now['wday'];
        $this->run_minute = $this->date_now['minute'];
        $this->run_hour   = $this->date_now['hour'];
        $this->run_month  = $this->date_now['month'];
        $this->run_year   = $this->date_now['year'];
        
        if ($task->task_week_day == -1 and $task->task_month_day == -1) {
            $day_set = 0;
        }
        
        if ($task->task_minute == -1) {
            $min_set = 0;
        }
        
        if ($task->task_week_day == -1) {
            if ($task->task_month_day != -1) {
                $this->run_day = $task->task_month_day;
                $day_increment = 'month';
            } else {
                $this->run_day = $this->date_now['mday'];
                $day_increment = 'anyday';
            }
        } else {
            //-----------------------------------------
            // Calc. next week day from today
            //-----------------------------------------
            
            $this->run_day = $this->date_now['mday'] + ($task->task_week_day - $this->date_now['wday']);
            
            $day_increment = 'week';
        }
        
        //-----------------------------------------
        // If the date to run next is less
        // than today, best fetch the next
        // time...
        //-----------------------------------------
        
        if ($this->run_day < $this->date_now['mday']) {
            switch ($day_increment) {
                case 'month':
                    $this->_addMonth();
                    break;
                case 'week':
                    $this->_addDay(7);
                    break;
                default:
                    $this->_addDay();
                    break;
            }
        }
            
        //-----------------------------------------
        // Sort out the hour...
        //-----------------------------------------
        
        if ($task->task_hour == -1) {
            /* If week, month are -1 and min =0 then on the hour, otherwise: */
            if (! $day_set and $task->task_minute == 0) {
                $this->_addHour(1);
            } else {
                $this->run_hour = $this->date_now['hour'];
            }
        } else {
            //-----------------------------------------
            // If ! min and ! day then it's
            // every X hour
            //-----------------------------------------
            
            if (! $day_set and ! $min_set) {
                $this->_addHour($task->task_hour);
            } else {
                $this->run_hour = $task->task_hour;
            }
        }
        
        //-----------------------------------------
        // Can we run the minute...
        //-----------------------------------------
        
        if ($task->task_minute == -1) {
            $this->_addMinute();
        } else {
            if ($task->task_hour == -1 and ! $day_set) {
                //-----------------------------------------
                // Runs every X minute..
                //-----------------------------------------
                
                $this->_addMinute($task->task_minute);
            } else {
                //-----------------------------------------
                // runs at hh:mm
                //-----------------------------------------
                
                $this->run_minute = $task->task_minute;
            }
        }
        
        if ($this->run_hour <= $this->date_now['hour'] and $this->run_day == $this->date_now['mday']) {
            
            if ($task->task_hour == -1) {
                
                //-----------------------------------------
                // Every hour...
                //-----------------------------------------
                
                if ($this->run_hour == $this->date_now['hour'] and $this->run_minute <= $this->date_now['minute']) {
                    $this->_addHour();
                }

            } else {

                //-----------------------------------------
                // Every X hour, try again in x hours
                //-----------------------------------------
                
                if (! $day_set and ! $min_set) {
                    $this->_addHour($task->task_hour);
                }
                
                //-----------------------------------------
                // Specific hour, try tomorrow
                //-----------------------------------------
                
                elseif (! $day_set) {
                    $this->_addDay();
                } else {
                    //-----------------------------------------
                    // Oops, specific day...
                    //-----------------------------------------
                    
                    switch ($day_increment) {
                        case 'month':
                            $this->_addMonth();
                            break;
                        case 'week':
                            $this->_addDay(7);
                            break;
                        default:
                            $this->_addDay();
                            break;
                    }
                }
            }
        }
        
        //-----------------------------------------
        // Return stamp...
        //-----------------------------------------
        $next_run = mktime($this->run_hour, $this->run_minute, 0, $this->run_month, $this->run_day, $this->run_year);
         
        return $next_run;
    }   


    /**
     * get datatable json tasks 
     *
     * @param	$data
     * @return	json
     */
    public function dataTableTasks($data)
    {
        
        $tasks = ReportTask::query();

        return  Datatables::of($tasks)
                ->editColumn('title', function ($task) {
                    return $task->title;
                })
                ->editColumn('description', function ($task) {
                    return $task->task_description;
                })
                ->editColumn('created', function ($task) {
                    return date('m/d/Y', $task->created);
                })
                ->editColumn('next_run', function ($task) {
                        return date('m/d/Y H:i', strtotime($task->next_run_human));;
                })
                ->editColumn('active', function ($task) {
                        return $task->task_enabled ? 'Yes' : 'No';
                })
                ->editColumn('subject', function ($task) {
                        return $task->subject;
                })
                ->editColumn('emails', function ($task) {
                        return $task->task_emails;
                })
                ->editColumn('file_name', function ($task) {
                        return $task->file_name;
                })
                ->editColumn('date_range', function ($task) {

                        if (intval($task->days_prior) > 0) {
					        return $task->days_prior . ' Days & Prior';
					    } elseif (intval($task->date_range) > 0) {
					        return $task->date_range . ' Days';
					    } else {
					        return $this->getTaskDateRangeTitle($task->date_range);
					    }
                })
                ->editColumn('on_weekends', function ($task) {
                        return $task->task_weekends ? 'No' : 'Yes';
                })
                ->editColumn('options', function ($task) {
                    return view('admin::manager-reports.tasks.partials._options', compact('task'))->render();
                })
                ->rawColumns(['options'])
                ->make(true);
    }

    /*
   	* task date ranges
    */
    public function getTaskDateRange()
	{
	    return array(
	        'day' => 'Current Day',
	        'week' => 'Current Week',
	        'month' => 'Current Month',
	    );
	}

	/*
   	* task date ranges title
    */
	public function getTaskDateRangeTitle($type)
	{
	    $types = $this->getTaskDateRange();
	    return isset($types[$type]) ? $types[$type] : 'N/A';
	}

	/**
     * Add a month to the next run timestamp
     *
     * @access	protected
     * @return	@e void
     */
    protected function _addMonth()
    {
        if ($this->date_now['month'] == 12) {
            $this->run_month = 1;
            $this->run_year++;
        } else {
            $this->run_month++;
        }
    }
    
    /**
     * Add a day to the next run timestamp
     *
     * @access	protected
     * @param	integer		Number of days to add
     * @return	@e void
     */
    protected function _addDay($days=1)
    {
        if ($this->date_now['mday'] >= (date('t', $this->time_now) - $days)) {
            $this->run_day = ($this->date_now['mday'] + $days) - date('t', $this->time_now);
            $this->_addMonth();
        } else {
            $this->run_day += $days;
        }
    }
    
    /**
     * Add an hour to the next run timestamp
     *
     * @access	protected
     * @param	integer		Number of hours to add
     * @return	@e void
     */
    protected function _addHour($hour=1)
    {
        if ($this->date_now['hour'] >= (24 - $hour)) {
            $this->run_hour = ($this->date_now['hour'] + $hour) - 24;
            $this->_addDay();
        } else {
            $this->run_hour += $hour;
        }
    }
    
    /**
     * Add a minute to the next run timestamp
     *
     * @access	protected
     * @param	integer		Number of minutes to add
     * @return	@e void
     */
    protected function _addMinute($mins=1)
    {
        if ($this->date_now['minute'] >= (60 - $mins)) {
            $this->run_minute = ($this->date_now['minute'] + $mins) - 60;
            $this->_addHour();
        } else {
            $this->run_minute += $mins;
        }
    }
}    