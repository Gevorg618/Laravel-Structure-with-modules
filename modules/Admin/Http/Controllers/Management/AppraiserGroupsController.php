<?php

namespace Modules\Admin\Http\Controllers\Management;

use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Management\AppraiserGroupsRepository;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Http\Requests\Appraiser\CreateAppraiserGroupRequest;
use Modules\Admin\Http\Requests\Appraiser\EditAppraiserGroupRequest;
use App\Models\Appraiser\AppraiserGroup;

class AppraiserGroupsController extends AdminBaseController
{

    /**
     * Object of AppraiserGroupsRepository class
     *
     * @var appraiserGroupsRepo
     */
    private $appraiserGroupsRepo;

    /**
     * Object of UserRepository class
     *
     * @var userRepo
     */
    private $userRepo;
    
    /**
     * Create a new instance of AppraiserGroupsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->appraiserGroupsRepo = new AppraiserGroupsRepository();
        $this->userRepo = new UserRepository();
    }


    /**
     * GET /admin/management/appraiser-groups/index
     *
     * Appraiser groups index page
     *
     * @return view
     */
    public function index()
    {
        return view('admin::management.appraiser.index');
    }

    /**
     * GET /admin/management/appraiser-groups/data
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $groups
     */
    public function data(Request $request)
    {
        // dd($request->all());
        // check if request is ajax
        if ($request->ajax()) {
            
            // get appraiser group users table s with managers and created by date
            $groups = $this->appraiserGroupsRepo->appraiserGroupsDataTables(); AppraiserGroup::withCount('users')->with('manager', 'createdBy')->get();

            return $groups;
        }
    }

    /**
     * GET /admin/management/appraiser-groups/create
     *
     * create Appraiser group page
     *
     * @return view
     */
    public function create()
    {
        return view('admin::management.appraiser.create');
    }

    /**
     * GET /admin/management/appraiser-groups/id/{id}
     *
     * edit Appraiser group page
     *
     * @param integer $id
     * 
     * @return view
     */
    public function edit($id)
    {
        $group = $this->appraiserGroupsRepo->getOneWithManager($id);
        
        return view('admin::management.appraiser.edit', compact('group'));
    }

    /**
     * POST /admin/management/appraiser-groups/id/{id}
     *
     * edit Appraiser group data
     *
     * @param integer $id
     * @param EditAppraiserGroupRequest $request
     *
     * @return view
     */
    public function update($id, EditAppraiserGroupRequest $request)
    {
        $requestData = $request->only('title', 'description', 'managerid');
        $group = $this->appraiserGroupsRepo->getOne($id);
        if ($group) {
            try {
                DB::beginTransaction();
                $group->update($requestData);
                DB::commit();
                Session::flash('success', 'Appraiser Group Successfully Updated');

                return view('admin::management.appraiser.index');

            } catch (Exception $exception) {
                DB::rollBack();
                Session::flash('error', $exception->getMessage());

                return view('admin::management.appraiser.index');
            } 
        } else {
            Session::flash('error', 'Appraiser Group not found');

            return view('admin::management.appraiser.index');
        }
        
    }

    /**
     * POST /admin/management/appraiser-groups/store 
     *
     * create Appraiser group
     *
     * @param CreateAppraiserGroupRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateAppraiserGroupRequest $request)
    {
        $requestData = $request->only('title', 'description', 'managerid');
        
        try {
            DB::beginTransaction();
            $appraiserGroup = new AppraiserGroup($requestData);
            $appraiserGroup->save();
            DB::commit();
            Session::flash('success', 'Appraiser Group Successfully Created');

            return redirect()->route('admin.management.appraiser.edit', $appraiserGroup->id);

        } catch (Exception $exception) {
            DB::rollBack();
            $message = $exception->getMessage();
            Session::flash('error', $message);

            return view('admin::management.appraiser.index');
        }

    }

    /**
     * GET /admin/management/appraiser-groups/managers 
     * 
     * get managers for autocomplete
     *
     * @param Request  $request
     * @param UserContract $userRepo
     *
     * @return JsonResponse
     */
    public function getManagers(Request $request, UserContract $userRepo)
    {
        $request = $request->input('input');
        $users = $userRepo->searchManagers($request);

        return response()->json($users);
    }

    /**
     * GET /admin/management/appraiser-groups/appraisers
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAppraisers(Request $request)
    {
        $request = $request->input('input');
        $users   = $this->userRepo->searchAppraisers($request);

        return response()->json($users);
    }

    /**
     * POST /admin/management/appraiser-groups/appraisers
     *
     * add appraiser to group
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function storeAppraiser(Request $request)
    {
        $request = $request->all();
        $group   = $this->appraiserGroupsRepo->getOne($request['group_id']);
        try {
            $group->appraisers()->attach($request['appraiser_id']);
        } catch (\Exception $exception) {
            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => $exception->getMessage()
            ];

            return response()->json($result);
        }
    }

    /**
     * POST /admin/management/appraiser-groups/appraisers
     *
     * add appraiser to group
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroyAppraiser(Request $request)
    {
        $request = $request->all();
        $group   = $this->appraiserGroupsRepo->getOne($request['group_id']);
        try {
            $group->appraisers()->detach($request['appraiser_id']);
            $count = $group->appraisers->count();
            $data = [
                'count' => $count,
            ];
            $result = [
                'success' => '1',
                'type'    => 'success',
                'data'    => $data
            ];

            return response()->json($result);
        } catch (\Exception $exception) {
            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => $exception->getMessage()
            ];

            return response()->json($result);
        }
    }
}