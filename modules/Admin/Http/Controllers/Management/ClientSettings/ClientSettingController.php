<?php

namespace Modules\Admin\Http\Controllers\Management\ClientSettings;


use Admin\Repositories\Appraisal\StatePricingVersionRepository;
use Admin\Repositories\Clients\ClientFileRepository;
use Admin\Repositories\Clients\ClientGroupNoteRepository;
use Admin\Repositories\Clients\ClientHistoryRepository;
use Admin\Repositories\Clients\ClientLogRepository;
use Admin\Repositories\LoanpurposeRepository;
use Admin\Repositories\UserGroupLogRepository;
use App\Models\Clients\Client;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Management\ClientSettings\ClientSettingsRequest;
use Modules\Admin\Repositories\ApiUserRepository;
use Modules\Admin\Repositories\Appraisal\OccupancyStatusRepository;
use Modules\Admin\Repositories\Clients\ClientSettingRepository;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\ApprType\ApprTypeRepository;
use Modules\Admin\Repositories\Appraisal\LoanTypesRepository;
use  Modules\Admin\Repositories\Appraisal\LoanReasonRepository;
use Modules\Admin\Repositories\Appraisal\PropertyTypeRepository;
use Modules\Admin\Repositories\Customizations\StatusRepository;
use Modules\Admin\Repositories\Lenders\LenderRepository;
use Validator;
use DB;


class ClientSettingController extends AdminBaseController
{


    /**
     * Object of ClientSettingRepository class
     *
     * @var clientSettingRepo
     */
    private $clientSettingRepo;


    /**
     * Object of ApprTypeRepository class
     *
     * @var apprTypeRepository
     */
    private $apprTypeRepository;

    /**
     * Object of LoanTypesRepository class
     *
     * @var loanTypesRepository
     */
    private $loanTypesRepository;


    /**
     * Object of LoanReasonRepository class
     *
     * @var loanReasonRepository
     */
    private $loanReasonRepository;


    /**
     * Object of PropertyTypeRepository class
     *
     * @var propertyTypeRepository
     */
    private $propertyTypeRepository;


    /**
     * Object of OccupancyStatusRepository class
     *
     * @var occupancyStatusRepository
     */
    private $occupancyStatusRepository;

    /**
     * Object of StatePricingVersionRepository class
     *
     * @var statePricingVersionRepository
     */
    private $statePricingVersionRepository;


    /**
     * Object of lenderRepository class
     *
     * @var lenderRepository
     */
    private $lenderRepository;


    /**
     * Object of StatusRepository class
     *
     * @var StatusRepository
     */
    private $statusRepository;


    /**
     * Object of ApiUserRepository class
     *
     * @var  apiUserRepository
     */
    private $apiUserRepository;

    /**
     * Object of LoanpurposeRepository class
     *
     * @var  loanpurposeRepository
     */
    private $loanpurposeRepository;


    /**
     * Object of ClientLogRepository class
     *
     * @var  clientLogRepository
     */
    private $clientLogRepository;


    /**
     * Object of ClientFileRepository class
     *
     * @var  clientFileRepository
     */
    private $clientFileRepository;


    /**
     * Object of ClientHistoryRepository class
     *
     * @var  clientHistoryRepository
     */
    private $clientHistoryRepository;

    /**
     * Object of ClientGroupNoteRepository class
     */
    private $clientGroupNoteRepository;


    /**
     * Object of UserGroupLogRepository class
     */
    private $userGroupLogRepository;


    /**
     * Create a new instance of ClientSettingController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientSettingRepo = new ClientSettingRepository();
        $this->apprTypeRepository = new ApprTypeRepository();
        $this->loanTypesRepository = new LoanTypesRepository();
        $this->loanReasonRepository = new LoanReasonRepository();
        $this->propertyTypeRepository = new PropertyTypeRepository();
        $this->occupancyStatusRepository = new OccupancyStatusRepository();
        $this->statePricingVersionRepository = new StatePricingVersionRepository();
        $this->lenderRepository = new LenderRepository();
        $this->statusRepository = new StatusRepository();
        $this->apiUserRepository = new ApiUserRepository();
        $this->loanpurposeRepository = new LoanpurposeRepository();
        $this->clientLogRepository = new ClientLogRepository();
        $this->clientFileRepository = new ClientFileRepository();
        $this->clientHistoryRepository = new ClientHistoryRepository();
        $this->clientGroupNoteRepository = new ClientGroupNoteRepository();
        $this->userGroupLogRepository = new UserGroupLogRepository();

    }

    /**
     * Index
     * @return view
     */
    public function index()
    {
        $superUsers = $this->clientSettingRepo->getSuperUsers();
        $sales = $this->clientSettingRepo->getSales();
        $states = $this->clientSettingRepo->getStates();
        return view('admin::management.client_settings.index', compact('superUsers', 'sales', 'states'));
    }

    public function data(Request $request)
    {
        return $this->clientSettingRepo->data($request);
    }


    /**
     * Show Add Client Form
     * @return view
     */
    public function create()
    {
        $states = $this->clientSettingRepo->getStates();
        return view('admin::management.client_settings.create', compact('states'));
    }

    /**
     * Insert New User
     * @param  CreateClient $request
     * @return redirect
     */
    public function store(ClientSettingsRequest $request)
    {

        $this->clientSettingRepo->logCreateGroup();
        $client = $this->clientSettingRepo->store($request->all());

        if (!$client) {
            return redirect()->back()->with('error', 'Something went wrong, please try again later.');
        }

        return redirect()->route('admin.management.client.settings')->with('success', ' Group created successfully.');
    }


    /**
     * Edit Client  View
     * @param  $id
     * @return view
     */
    public function edit($id)
    {

        $client = $this->clientSettingRepo->single($id);
        $states = $this->clientSettingRepo->getStates();
        $apprTypeListY = $this->apprTypeRepository->getApprTypeList(['active' => 'Y']);
        $getLoanTypesList = $this->loanTypesRepository->loanTypesList();
        $getReasonsList = $this->loanReasonRepository->loanReasonsList();
        $propertyTypeList = $this->propertyTypeRepository->propertyTypesList();
        $occupancyStatusesList = $this->occupancyStatusRepository->occupancyStatusesList();
        $statePricingVersionList = $this->statePricingVersionRepository->statePricingVersionList();
        $lenderList = $this->lenderRepository->lesaleLendersList();
        $apprTypeList = $this->apprTypeRepository->getApprTypeList([]);
        $apiUserList = $this->apiUserRepository->getAPIUser();
        $loanpurpose = $this->loanpurposeRepository->getLoanpurpose();
        $clientLogs = $this->clientLogRepository->get($id);
        $clientHistories = $this->clientHistoryRepository->get($id);
        $superUsers = $this->clientSettingRepo->getSuperUsers();
        $sales = $this->clientSettingRepo->getSales();
        $notes = $this->clientGroupNoteRepository->get($id);
        $apLogs = $this->userGroupLogRepository->getUserLogs($id);
        return view('admin::management.client_settings.edit',
            compact('client', 'states', 'apprTypeListY', 'getLoanTypesList',
                'getReasonsList', 'propertyTypeList', 'occupancyStatusesList', 'statePricingVersionList',
                'lenderList', 'apprTypeList', 'apiUserList', 'loanpurpose', 'clientLogs',
                'clientHistories', 'superUsers', 'sales', 'notes', 'apLogs'));
    }


    /**
     * Update client details
     * @param  $id [description]
     * @param  CreateClient $request
     * @return boosl
     */
    public function update($id, ClientSettingsRequest $request)
    {
        $this->clientSettingRepo->logCreateGroup();
        $this->clientSettingRepo->historyCreateGroup();
        $result = $this->clientSettingRepo->update($id, $request->all());
        $this->clientSettingRepo->uploadPdfFile($id, $request);
        $notes = $this->clientSettingRepo->addNoteGroup($request->all());
        if (!$result) {
            return redirect()->back()->with('error', 'Something went wrong, please try again later.');
        }

        if ($notes) {
            return redirect()->back()->with('success', 'Note added.');
        }

        return redirect()->back()->with('success', ' Group updated successfully.');
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPdfFile($id)
    {
        return $this->clientSettingRepo->downloadFile($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function pdfFileDelete($id)
    {
        $this->clientSettingRepo->fileDelete($id);
        return redirect()->back()->with('success', ' Delete file  successfully.');
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function searchOrders(Request $request)
    {
        $orders = $this->clientSettingRepo->searchOrders($request->all());
        return view('admin::management.client_settings.partials._orders_table', compact('orders'))
            ->render();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clientChangeActive(Request $request)
    {
        $this->clientSettingRepo->logCreateGroup();
        $this->clientSettingRepo->changeActive($request->all());
        $client = $this->clientSettingRepo->single($request['id']);

        if ($client->active) {
            return redirect()->back()->with('success', 'Group Enabled.');
        } else {
            return redirect()->back()->with('success', 'Group Disabled.');

        }
    }








    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function addUser(Request $request)
    {
        $validator = $this->validate($request, [
            'email' => 'required|unique:user|max:255',
        ]);

        if ($validator) {
            $userUserData = $this->clientSettingRepo->quickAddUser($request->all());
            return view('admin::management.client_settings.partials._user_table', compact('userUserData'))
                ->render();
        }

    }


    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function addLog(Request $request)
    {
        $apLogs = $this->clientSettingRepo->addApLog($request->all());
        return view('admin::management.client_settings.partials._ap_log', compact('apLogs'))
            ->render();
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppraisers(Request $request)
    {
        $data = $request->all();
        $input = $data['input'];
        $key = $data['key'];
        $groupid = $data['groupid'];
        $users = $this->clientSettingRepo->searchAppraisers($input, $key, $groupid);
        return response()->Json($users);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $data = $request->all();
        $input = $data['input'];
        $key = $data['key'];
        $groupid = $data['groupid'];
        $users = $this->clientSettingRepo->searchUsers($input, $key, $groupid);
        return response()->Json($users);
    }

}
