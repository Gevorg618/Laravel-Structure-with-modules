<?php 

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ManagerReport\ClientSettingsRepository;
use App\Models\Clients\Client;
use Maatwebsite\Excel\Facades\Excel;

class ClientSettingsController extends AdminBaseController 
{
    /**
     * Object of ClientSettingsRepository class
     *
     * @var clientSettingRepo
     */
    private $clientSettingRepo;
    
    /**
     * Create a new instance of ClientSettingsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientSettingRepo = new ClientSettingsRepository();
    }

    /**
    *
    * User Report Generator index 
    *
    * @return view
    */
    public function index()
    {   

        $clients = Client::getAllClients()->pluck('descrip', 'id');
        $reportList = $this->clientSettingRepo->getReportList();
        
        return view('admin::manager-reports.client-setting.index', compact('clients', 'reportList'));
    }

    /**
    *
    * User Report Generator data  
    *
    * @return view
    */
    public function data(Request $request)
    {   

        if ($request->ajax()) {
            $data = $this->clientSettingRepo->reportListDatatable($request->get('client'), 
            $request->get('daterange'), $request->get('date_type'), $request->get('report_type'));
            return $data;
        } else {
            return null;
        }
    }

    /**
    *
    * User Report Generator download 
    *
    * @return view
    */
    public function download(Request $request)
    {   
        
        $groupNotes = $this->clientSettingRepo->buildCSVDocument($request->get('client'), 
        $request->get('daterange'), $request->get('date_type'), $request->get('report_type'));
        
        if ($groupNotes) {

            Excel::create('Group-Notes-Report', function($excel) use($groupNotes) {
               $excel->sheet('sheet1', function($sheet) use ($groupNotes) {
                   $sheet->fromArray($groupNotes);
               });
            })->download('xlsx');

        } else {
            \Session::flash('error', 'There is no data for download');
            return redirect()->route('admin.reports.client.setting.index');
        }
    }

}