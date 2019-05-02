<?php 

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ManagerReport\ReconsiderationRepository;
use Maatwebsite\Excel\Facades\Excel;

class ReconsiderationController extends AdminBaseController 
{
    /**
     * Object of ReconsiderationRepository class
     *
     * @var reconsiderationRepo
     */
    private $reconsiderationRepo;
    
    /**
     * Create a new instance of ReconsiderationController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->reconsiderationRepo = new ReconsiderationRepository();
    }

    /**
    *
    * Reconsideration index 
    *
    * @return view
    */
    public function index()
    {   
        return view('admin::manager-reports.reconsideration.index');
    }

    /**
    *
    * Reconsideration download 
    *
    * @return view
    */
    public function download(Request $request)
    {   
        $items = $this->reconsiderationRepo->generateDataForDownload($request->get('daterange'));
        

        if ($items) {

            $headers = $this->reconsiderationRepo->csvHeaders();

            foreach ($items as $key => $value) {
               $dataCsv [$key] = array_combine($headers, $value);
            }

            Excel::create('Reconsideration-Report-'.$request->get('daterange'), function($excel) use($dataCsv) {
               $excel->sheet('sheet1', function($sheet) use ($dataCsv) {
                   $sheet->fromArray($dataCsv);
               });
            })->download('xlsx');

        } else {
            \Session::flash('error', 'There is no data for download');
            return redirect()->route('admin.reports.reconsideration.index');
        }
    }

}