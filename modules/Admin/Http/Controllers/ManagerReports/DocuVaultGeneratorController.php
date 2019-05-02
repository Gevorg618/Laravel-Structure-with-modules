<?php 

namespace Modules\Admin\Http\Controllers\ManagerReports;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ManagerReport\DocuVaultGeneratorRepository;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use Maatwebsite\Excel\Facades\Excel;

class DocuVaultGeneratorController extends AdminBaseController 
{
    /**
     * Object of DocuVaultGeneratorRepository class
     *
     * @var DocuVaultGeneratorRepo
     */
    private $docuVaultGeneratorRepo;
    
    /**
     * Create a new instance of DocuVaultGeneratorController class.
     *
     * @return void
     */
    public function __construct()
    {

        $this->docuVaultGeneratorRepo = new DocuVaultGeneratorRepository();
    }

    /**
    *
    * Docu Vault Report Generator index 
    *
    * @return view
    */
    public function index()
    {   
        $clients = Client::getAllClients()->pluck('descrip', 'id');
        $lenders = UserGroupLender::get()->pluck('lender', 'id');
        return view('admin::manager-reports.docu-vault.index', compact('clients', 'lenders'));
    }

     /**
    *
    * Docu Vault Report Generator download 
    *
    * @return view
    */
    public function download(Request $request)
    {   
        
        $data = $this->docuVaultGeneratorRepo->buildCSVDocument($request->get('client'), 
        $request->get('daterange'), $request->get('date_type'), $request->get('lenders'));

        $reportsArray = [];

        foreach ($data as $key => $value) {
            $reportsArray[$key] = (array)$value;   
        }
        
        Excel::create('reports', function($excel) use($reportsArray) {
            $excel->sheet('sheet1', function($sheet) use ($reportsArray) {
                $sheet->fromArray($reportsArray);
            });
        })->download('xlsx');
    }

}