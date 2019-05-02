<?php 

namespace Modules\Admin\Http\Controllers\Documents;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Documents\GlobalDocumentRequest;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Modules\Admin\Repositories\Lenders\LenderRepository;
use Modules\Admin\Repositories\Clients\ClientRepository;
use Modules\Admin\Repositories\Document\OrderLocationRepository;
use Modules\Admin\Repositories\Appraisal\LoanTypesRepository;
use Modules\Admin\Repositories\Appraisal\LoanReasonRepository;
use Modules\Admin\Repositories\Appraisal\PropertyTypeRepository;
use Modules\Admin\Repositories\Appraisal\OccupancyStatusRepository;
use Modules\Admin\Repositories\Document\GlobalDocumentsRepository;

class GlobalDocumentsController extends AdminBaseController 
{

    /**
     * Object of GlobalDocumentsRepository class
     *
     * @var documentRepo
     */
    public $documentRepo;

    /**
     * Object of StatesRepository class
     *
     * @var statesRepository
     */
    public $statesRepository;

    /**
     * Object of TypesRepository class
     *
     * @var typesRepository
     */
    public $typesRepository;

    /**
     * Object of LenderRepository class
     *
     * @var lenderRepository
     */
    public $lenderRepository;

    /**
     * Object of ClientRepository class
     *
     * @var clientRepository
     */
    public $clientRepository;

    /**
     * Object of OrderLocationRepository class
     *
     * @var OrderLocationRepository
     */
    public $orderLocationRepository;

    /**
     * Object of LoanTypesRepository class
     *
     * @var LoanTypesRepository
     */
    public $loanTypesRepository;

    /**
     * Object of LoanReasonRepository class
     *
     * @var loanReasonRepository
     */
    public $loanReasonRepository;

    /**
     * Object of PropertyTypeRepository class
     *
     * @var propertyTypeRepository
     */
    public $propertyTypeRepository;

    /**
     * Object of OccupancyStatusRepository class
     *
     * @var occupancyStatusRepository
     */
    public $occupancyStatusRepository;

    /**
     * Create a new instance of GlobalDocumentsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->documentRepo  = new GlobalDocumentsRepository();
        $this->statesRepository  = new StatesRepository();
        $this->typesRepository = new TypesRepository();
        $this->lenderRepository = new LenderRepository();
        $this->clientRepository = new ClientRepository();
        $this->orderLocationRepository = new OrderLocationRepository();
        $this->loanTypesRepository = new LoanTypesRepository();
        $this->loanReasonRepository = new LoanReasonRepository();
        $this->propertyTypeRepository = new PropertyTypeRepository();
        $this->occupancyStatusRepository = new OccupancyStatusRepository();
    }

    /**
     * GET /admin/document/global/index
     *
     * Global Document index page
     *
     * @return view
     */
    public function index()
    {
        return view('admin::document.global.index');
    }

    /**
     * GET /admin/document/global/data
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $documents
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {

            $documents = $this->documentRepo->documentsDataTables();

            return $documents;
        }
    }

    /**
     * GET /admin/document/global/create
     *
     * Global Document create page
     *
     * @return view
     */
    public function create()
    {
        $lesaleLenders = $this->lenderRepository->lesaleLenders()->pluck('lender', 'id');
        $clients = $this->clientRepository->clients()->pluck('descrip', 'id');
        $states = $this->statesRepository->getStates()->pluck('state', 'abbr');
        $locations = $this->orderLocationRepository->locations()->pluck('name', 'id');
        $types = $this->typesRepository->createTypesArray();
        $loanTypes = $this->loanTypesRepository->loanTypes()->pluck('descrip', 'id');
        $loanReasons = $this->loanReasonRepository->loanReasons()->pluck('descrip', 'id');
        $propertyTypes = $this->propertyTypeRepository->propertyTypes()->pluck('descrip', 'id');
        $occupancyStatuses = $this->occupancyStatusRepository->occupancyStatuses()->pluck('descrip', 'id');

        return view('admin::document.global.create', compact('lesaleLenders', 'clients', 'states', 'locations', 'types', 'loanTypes', 'loanReasons', 'propertyTypes', 'occupancyStatuses'));
    }

    /**
     * POST /admin/document/global/store
     *
     * Global Document store 
     *
     * @return view
     */
    public function store(GlobalDocumentRequest $request)
    {
        $globalDocumentData = $request->only('file_name', 'is_active' , 'is_client_visible', 'is_appr_visible', 'created_by', 'created_date', 'file_location');
        $attachData = $request->except('_token', 'file_name', 'is_active' , 'is_client_visible', 'is_appr_visible', 'created_by', 'created_date', 'file_location');
        $file = $request->file('file_location');

        $createdDoc = $this->documentRepo->create($globalDocumentData, $attachData, $file);


        if ($createdDoc['success']) {

            Session::flash('success', $createdDoc['message']);

            return redirect()->route('admin.document.global.index');
        
        } else {

            Session::flash('error', $createdDoc['message']);

            return redirect()->route('admin.document.global.index');
        }
    }

    /**
     * GET /admin/document/global/delete
     *
     * Delete Documents
     *
     * @param integer $id
     *
     * @return view
     */
    public function delete($id)
    {
        $isDeleted = $this->documentRepo->delete($id);

        if ($isDeleted) {

            Session::flash('success', $isDeleted['message']);
            
            return redirect()->route('admin.document.global.index');

        } else {

            Session::flash('error', $isDeleted['message'] );
            
            return redirect()->route('admin.document.global.index');
        }
    }


    /**
     * GET /admin/document/global/edit
     *
     * Edit Custom Page page
     *
     * @param integer $id
     *
     * @return view
     */
    public function edit($id)
    {
        $document = $this->documentRepo->getDocumentById($id);
        
        if ($document) {

            $lesaleLenders = $this->lenderRepository->lesaleLenders()->pluck('lender', 'id');
            $clients = $this->clientRepository->clients()->pluck('descrip', 'id');
            $states = $this->statesRepository->getStates()->pluck('state', 'abbr');
            $locations = $this->orderLocationRepository->locations()->pluck('name', 'id');
            $types = $this->typesRepository->createTypesArray();
            $loanTypes = $this->loanTypesRepository->loanTypes()->pluck('descrip', 'id');
            $loanReasons = $this->loanReasonRepository->loanReasons()->pluck('descrip', 'id');
            $propertyTypes = $this->propertyTypeRepository->propertyTypes()->pluck('descrip', 'id');
            $occupancyStatuses = $this->occupancyStatusRepository->occupancyStatuses()->pluck('descrip', 'id');

            return view('admin::document.global.edit', compact('document','lesaleLenders', 'clients', 'states',
             'locations', 'types', 'loanTypes', 'loanReasons', 'propertyTypes', 'occupancyStatuses'));

        } else {
            
            Session::flash('error', 'Document is not found.');

            return redirect()->route('admin.document.global.index');
        }
    }

    /**
     * PUT /admin/document/global/update
     *
     * Update Document
     *
     * @param integer $id
     * @param Request $request
     *
     * @return view
     */
    public function update($id, GlobalDocumentRequest $request)
    {
        $globalDocumentData = $request->only('file_name', 'is_active' , 'is_client_visible', 'is_appr_visible', 'created_by', 'created_date', 'file_location');
        $attachData = $request->except('_token', '_method', 'file_name', 'is_active' , 'is_client_visible', 'is_appr_visible', 'created_by', 'created_date', 'file_location');
        $file = $request->file('file_location');

        $isUpdated = $this->documentRepo->update($id, $globalDocumentData, $attachData, $file);

        if ($isUpdated['success']) {

            Session::flash('success', $isUpdated['message']);

            return redirect()->route('admin.document.global.index');
        
        } else {

            Session::flash('error', $isUpdated['message']);

            return redirect()->route('admin.document.global.index');
        }
    }

}