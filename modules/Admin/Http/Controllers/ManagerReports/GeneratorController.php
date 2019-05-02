<?php 

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ManagerReport\GeneratorRepository;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Repositories\Management\WholesaleLenders\LendersRepository;
use Modules\Admin\Repositories\ManagerReport\TaskRepository;

class GeneratorController extends AdminBaseController 
{
    /**
     * Object of GeneratorRepository class
     *
     * @var generatorRepo
     */
    private $generatorRepo;
    
    /**
     * Create a new instance of GeneratorController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->generatorRepo = new GeneratorRepository();
        $this->lenderRepo = new LendersRepository();
    }

    /**
     *
     * Generator Report index 
     *
     * @return view
     */
    public function index()
    {   
        $clients = Client::getAllClients()->pluck('descrip', 'id');
        $lenders = $this->lenderRepo->getWholesaleLenders()->pluck('lender', 'id');
        $dateTypes = $this->generatorRepo->dateTypes();
        $columns = $this->generatorRepo->getManagerReportHeaders();
        $statuses = $this->generatorRepo->getStatuses();
        $teams = $this->generatorRepo->getTeams();
        $apprTypes = $this->generatorRepo->allTypes();
        $states = $this->generatorRepo->getStates();

        return view('admin::manager-reports.generator.index', compact('clients', 'lenders', 'dateTypes', 'columns', 'statuses', 'teams', 'apprTypes', 'states'));
    }

    /**
     *
     * Generator download 
     * 
     * @param Request $request
     * 
     * @return view
     */
    public function download(Request $request)
    {   
        $dataCsv = $this->generatorRepo->getItems($request->all());

        if ($dataCsv) {
            Excel::create('User-Manager-Report', function($excel) use($dataCsv) {

                $excel->sheet('sheet1', function($sheet) use ($dataCsv) {
                    
                     // Set Array
                    $sheet->fromArray($dataCsv);      
                   
               });
        
            })->download('xlsx');

            Session::flash('success', 'The report was succesfully downloaded !');
            return redirect()->route('admin.reports.generator.index');
        } else {
            Session::flash('warning', 'There is no data for download !');
            return redirect()->route('admin.reports.generator.index')->withInput();
        }
        

    }

    /**
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $users
     */
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->generatorRepo->searchAppr($request->get('query'));
            return response()->json($users);
        }
    }

    /**
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $view
     */
    public function renderTask(Request $request)
    {

        if ($request->ajax()) {
            
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

            $view = view('admin::manager-reports.generator._partials._save-as-task', compact('m', 'h', 'w', 'mD', 'data'))->render();

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
    public function createTask(Request $request)
    {
        
        $taskRepo = new TaskRepository();
        $dataCreate = [
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

        $newTaskId = $taskRepo->create($dataCreate);
        $task = $taskRepo->getTask($newTaskId);
        $nextRunTime = $taskRepo->generateNextRun($task);
        $nextRunTimeHuman  = date('Y-m-d H:i:s', $nextRunTime);

        $updatedNextRun = $taskRepo->update($newTaskId, ['task_next_run' => $nextRunTime, 'next_run_human' => $nextRunTimeHuman]);
        
        if ($updatedNextRun) {
            Session::flash('success', 'Task was successfully created !');
            return redirect()->route('admin.reports.generator.index');
        } else {
            Session::flash('error', 'There is error for task creating !');
            return redirect()->route('admin.reports.generator.index');
        }
    }

}
