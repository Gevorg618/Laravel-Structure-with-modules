<?php 

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ManagerReport\UserGeneratorRepository;
use App\Models\Clients\Client;
use Maatwebsite\Excel\Facades\Excel;

class UserGeneratorController extends AdminBaseController 
{
    /**
     * Object of UserGeneratorRepository class
     *
     * @var userGeneratorRepo
     */
    private $userGeneratorRepo;
    
    /**
     * Create a new instance of UserGeneratorController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userGeneratorRepo = new UserGeneratorRepository();
    }

    /**
    *
    * User Report Generator index 
    *
    * @return view
    */
    public function index()
    {   
        $statuses = ['' => '-- All --'] + $this->userGeneratorRepo->getStatuses()->toArray();
        $userTypes = userAllTypes();
        $states = ['' => '-- All States --'] +  getStates();
        $columns = getUserReportManagerHeaders();
        $clients = Client::getAllClients()->pluck('descrip', 'id');
    
        return view('admin::manager-reports.user-generator.index', compact('statuses', 'userTypes', 'states', 'columns', 'clients'));
    }

    /**
    *
    * download report
    *
    * @return view
    */
    public function download(Request $request)
    {   
        $data = $this->userGeneratorRepo->generateDataForDownload($request->all());
        $items  = $data[0];
        $headers = $data[1];
        $dataCsv = [];

        if ($items) {

            foreach ($items as $key => $value) {
               $dataCsv [$key] = array_combine($headers, $value);
            }

            Excel::create('User-Manager-Report', function($excel) use($dataCsv) {
               $excel->sheet('sheet1', function($sheet) use ($dataCsv) {
                   $sheet->fromArray($dataCsv);
               });
            })->download('xlsx');

        } else {
            \Session::flash('error', 'There is no data for download');
            return redirect()->route('admin.reports.user.generator.index');
        }
    }
}