<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use File;
use Excel;
use Session;
use Redirect;
use Storage;
use Illuminate\Http\Request;
use App\Models\Clients\Client;
use App\Models\Documents\UserDoc;
use App\Models\Appraisal\OrderLog;
use App\Models\Customizations\Status;
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Exports\Accounting\Payables\PayablesExport;
use Modules\Admin\Http\Requests\Accounting\PayableManagerRequest;
use Modules\Admin\Repositories\Accounting\PayableManagerRepository;

class PayableManagerController extends AdminBaseController
{
    /**
     * Object of PayableManagerRepository class
     *
     * @var payableManagerRepo
     */
    private $payableManagerRepo;

    /**
     * Object of StatesRepository class
     *
     * @var stateRepo
     */
    private $stateRepo;

    /**
     * Create a new instance of PayableManagerController class.
     *
     * @return void
     */

    public function __construct()
    {
        $this->payableManagerRepo = new PayableManagerRepository();
        $this->stateRepo = new StatesRepository();

    }

    /**
     * Index page for Accounting Payable Revert
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $states = $this->stateRepo->getStates()->pluck('state', 'abbr');
        $clients = Client::getAllClients()->pluck('descrip', 'id');
        $statuses = Status::getStatuses()->pluck('descrip', 'id')->prepend('Nothing selected', '');
        return view('admin::accounting.payable-manager.index', compact('states', 'clients', 'statuses'));
    }

    /**
     * method get data for showing with ajax datatable
     *
     * @param PayableManagerRequest $request
     *
     * @return json
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            return  $this->payableManagerRepo->generateDatatables($request->get('formData'));
        }
    }

    /**
     * apply paymnet
     *
     * @param Request $request
     *
     * @return json
     */
    public function applyPaymnet(Request $request)
    {
        if ($request->ajax()) {
            $response = $this->payableManagerRepo->applyPayment($request->all());
            $dataCsv = $response['dataCsv']['data_csv'];

            Excel::store(new PayablesExport($dataCsv), $response['dataCsv']['file_name'], 'exports');

            return [
              'items' => $response['items'],
              'file_name' => $response['dataCsv']['file_name']
            ];
        }
    }

    /**
     * Read file
     *
     * @param Request $request
     *
     */
    public function read(Request $request)
    {
        $name = $request->get('file');
        $exists = Storage::disk('exports')->exists($name);

        abort_unless($exists, 404, 'File was not found.');

        return Storage::disk('exports')->download($name, $name, [
          'Content-Disposition' => sprintf('attachment; filename="%s"', $name),
          'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function download(Request $request)
    {
        $data = $this->payableManagerRepo->downloadCsv($request->all());
        $dataCsv = $data['data_csv'];

        if ($dataCsv) {
            return Excel::download(new PayablesExport($dataCsv), $data['file_name']);
        } else {
            Session::flash('warning', 'There is no data to download!');
            return redirect()->route('admin.accounting.payable-manager.index');
        }
    }


    /**
     * @param $id
     * @return string
     */
    public function downloadDocument(UserDoc $document)
    {
        $signed =  $this->payableManagerRepo->downloadDocument($document);

        return Redirect::away($signed);
    }
}
