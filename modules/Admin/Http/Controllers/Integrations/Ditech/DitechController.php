<?php

namespace Modules\Admin\Http\Controllers\Integrations\Ditech;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Integrations\DitechRepository;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Requests\Integrations\Ditech\DitechRequest;

class DitechController extends AdminBaseController
{

    /**
     * Object of DitechRepository class
     *
     * @var ditechRepo
     */
    private $ditechRepo;
    
    /**
     * Create a new instance of DitechController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ditechRepo = new DitechRepository();
    }

    /**
     * Index page for Ditech report
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::integrations.ditech.index');
    }

    /**
     *
     * Ditech Manager Data  download 
     * 
     * @param DitechRequest $request
     * 
     * @return view
     */
    public function download(DitechRequest $request)
    {   
        
        $dataCsv = $this->ditechRepo->getItems($request->all());
        
        if ($dataCsv) {
            Excel::create('Ditech-Manager', function($excel) use($dataCsv) {

                $excel->sheet('sheet1', function($sheet) use ($dataCsv) {
                    
                     // Set Array
                    $sheet->fromArray($dataCsv);      
                   
               });
        
            })->download('xlsx');
        } else {
            \Session::flash('warning', 'There is no data for download !');
            return redirect()->route('admin.reports.ditech.index');
        }
    }
}
