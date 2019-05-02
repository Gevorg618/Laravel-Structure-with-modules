<?php 

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ManagerReport\TaskRepository;
use Modules\Admin\Repositories\ManagerReport\GeneratorRepository;
use Modules\Admin\Repositories\Management\WholesaleLenders\LendersRepository;
use App\Models\Clients\Client;


class TasksController extends AdminBaseController 
{
    /**
     * Object of TaskRepository class
     *
     * @var taskRepo
     */
    private $taskRepo;
    
    /**
     * Object of GeneratorRepository class
     *
     * @var generatorRepo
     */
    private $generatorRepo;

    /**
     * Object of LendersRepository class
     *
     * @var lenderRepo
     */
    private $lenderRepo;

    /**
     * Create a new instance of TasksController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->taskRepo = new TaskRepository();
        $this->generatorRepo = new GeneratorRepository();
        $this->lenderRepo = new LendersRepository();
    }

    /**
     *
     * Tasks index 
     *
     * @return view
     */
    public function index()
    {   
        return view('admin::manager-reports.tasks.index');
    }

    /**
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $tasks
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {

            $tasks = $this->taskRepo->dataTableTasks($request->all());

            return $tasks;
        }
    }

    /**
     *
     * Tasks remove 
     *
     * @return view
     */
    public function destroy($id)
    {   
        $removedTask = $this->taskRepo->remove($id);

        if ($removedTask) {
            Session::flash('success', 'Task was successfully deleted !');
            return redirect()->route('admin.reports.tasks.index');
        } else {
            Session::flash('error', 'Task not found !');
            return redirect()->route('admin.reports.tasks.index');
        }
    }


    /**
     *
     * edit task view
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit($id)
    {

        $task = $this->taskRepo->getTask($id);

        $clients = Client::getAllClients()->pluck('descrip', 'id');
        $lenders = $this->lenderRepo->getWholesaleLenders()->pluck('lender', 'id');
        $dateTypes = $this->generatorRepo->dateTypes();
        $columns = $this->generatorRepo->getManagerReportHeaders();
        $statuses = $this->generatorRepo->getStatuses();
        $teams = $this->generatorRepo->getTeams();
        $apprTypes = $this->generatorRepo->allTypes();
        $states = $this->generatorRepo->getStates();

        $data = unserialize($task->task_data);
        
        return view('admin::manager-reports.tasks.edit', compact('task', 'clients', 'lenders', 'dateTypes', 'columns', 'statuses', 'teams', 'apprTypes', 'states', 'data'));
    }

    /**
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $view
     */
    public function renderTask($id, Request $request)
    {
        if ($request->ajax()) {
            
            $task = $this->taskRepo->getTask($id);

            // minutes
            $m = $this->generatorRepo->getTaskMinutes();

            // Hours
            $h = $this->generatorRepo->getTaskHours();

            //  Week
            $w = $this->generatorRepo->getTaskWeekDays();

            // month Days
            $mD = $this->generatorRepo->getTaskMonthDays();

            $dateRange = explode("-", $request->get('daterange'));
            
            // Init
            $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
            $dateTo = date('Y-m-d', strtotime($dateRange[1]));
 
            $data = [
                'datefrom' => $dateFrom,
                'dateto'=> $dateTo,
                'client'=> $request->get('client'),
                'lender'=> $request->get('lenders'),
                'type'=> $request->get('type'),
                'status'=> $request->get('status'),
                'columns'=>$request->get('columns'),
                'datetype'=> $request->get('datetype'),
                'team'=> $request->get('team'),
                'state'=> $request->get('states'),
                'is_client_approval' => $request->get('is_client_approval'),
                'filter_appraiser_id' => $request->get('appraiser_name'),
            ];

            $view = view('admin::manager-reports.tasks.partials._form', compact('m', 'h', 'w', 'mD', 'data', 'task'))->render();

            return response()->json($view);
        }
    }


    /**
     *
     * create task
     *
     * @param Request $request
     *
     * @return resposne
     */
    public function update($id, Request $request)
    {
        
        $dataUpdate = [
            'title' => $request->get('title'),
            'task_week_day' => $request->get('weekday'),
            'task_month_day' => $request->get('monthday'),
            'task_hour' => $request->get('hours'),
            'task_minute' => $request->get('minutes'),
            'task_description' => $request->get('description'),
            'task_enabled' => $request->get('active'),
            'task_emails' => $request->get('emails'),
            'task_data' => $request->get('task_data'),
            'file_name' => $request->get('filename'),
            'subject' => $request->get('subject'),
            'content' => $request->get('content'),
            'created' => time(),
            'date_range' => intval($request->get('date_range_num')) > 0 ? $request->get('date_range_num') : $request->get('daterange'),
            'days_prior' => intval($request->get('days_prior')),
            'task_weekends' => $request->get('weekends'),
            'task_file' => $request->get('task_file'),
        ];
       
        $this->taskRepo->update($id, $dataUpdate);
        
        $task = $this->taskRepo->getTask($id);

        $nextRunTime = $this->taskRepo->generateNextRun($task);
        $nextRunTimeHuman  = date('Y-m-d H:i:s', $nextRunTime);

        $updatedNextRun = $this->taskRepo->update($id, ['task_next_run' => $nextRunTime, 'next_run_human' => $nextRunTimeHuman]);
        
        if ($updatedNextRun) {
            Session::flash('success', 'Task was successfully updated !');
            return redirect()->route('admin.reports.tasks.index');
        } else {
            Session::flash('error', 'There is error for task updated !');
            return redirect()->route('admin.reports.tasks.index');
        }
    }
    
}