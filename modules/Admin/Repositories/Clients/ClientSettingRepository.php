<?php

namespace Modules\Admin\Repositories\Clients;

use Admin\Repositories\Clients\ClientFileRepository;
use Admin\Repositories\Clients\ClientGroupNoteRepository;
use Admin\Repositories\Clients\ClientHistoryRepository;
use Admin\Repositories\Clients\ClientLogRepository;
use Admin\Repositories\LoanpurposeRepository;
use Admin\Repositories\UserGroupLogRepository;
use Admin\Repositories\Users\UserDataRepository;
use App\Models\Appraisal\Order;
use App\Models\Clients\Client;
use App\Models\Clients\PreferAppr;
use App\Models\ExcludeAppr;
use App\Models\Users\UserGroupRelation;
use Carbon\Carbon;
use Modules\Admin\Repositories\Appraisal\LoanTypesRepository;
use Modules\Admin\Repositories\Appraisal\PropertyTypeRepository;
use Modules\Admin\Repositories\ApprType\ApprTypeRepository;
use Modules\Admin\Repositories\Lenders\LenderRepository;
use Modules\Admin\Repositories\Users\UserRepository;
use Yajra\Datatables\Datatables;
use Modules\Admin\Repositories\Geo;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Users\User;


class ClientSettingRepository
{


    /**
     * Object of Client class
     */
    private $model;


    /**
     * Object of StatesRepo class
     */
    private $statesRepo;


    /**
     * Object of StatesRepo class
     */
    private $clientFileRepository;


    /**
     * Object of Order class
     */
    private $order;


    /**
     * Object of ClientLogRepository class
     */
    private $clientLogRepository;


    /**
     * Object of ClientHistoryRepository class
     */
    private $clientHistoryRepository;

    /**
     * Object of LoanTypesRepository class
     */
    private $loanTypesRepository;


    /**
     * Object of LoanpurposeRepository class
     */
    private $loanpurposeRepository;


    /**
     * Object of PropertyTypeRepository class
     */
    private $propertyTypeRepository;


    /**
     * Object of ApprTypeRepository class
     */
    private $apprTypeRepository;


    /**
     * Object of ClientGroupNoteRepository class
     */
    private $clientGroupNoteRepository;

    /**
     * Object of LenderRepository class
     */
    private $lenderRepository;


    /**
     * Object of UserRepository class
     */
    private $userRepository;


    /**
     * Object of UserRepository class
     */
    private $userDataRepository;


    /**
     * Object of UserGroupLogRepository class
     */
    private $userGroupLogRepository;


    /**
     * Object of User class
     */
    private $user;


    /**
     * Object of PreferAppr class
     */
    private $preferAppr;

    /**
     * Object of PreferAppr class
     */
    private $excludeAppr;

    /**
     * Object of UserGroupRelation class
     */
    private $userGroupRelation;


    public function __construct()
    {
        $this->model = new Client();
        $this->statesRepo = new Geo\StatesRepository();
        $this->clientFileRepository = new ClientFileRepository();
        $this->order = new Order();
        $this->clientLogRepository = new ClientLogRepository();
        $this->clientHistoryRepository = new ClientHistoryRepository();
        $this->loanTypesRepository = new LoanTypesRepository();
        $this->loanpurposeRepository = new LoanpurposeRepository();
        $this->propertyTypeRepository = new PropertyTypeRepository();
        $this->apprTypeRepository = new ApprTypeRepository();
        $this->clientGroupNoteRepository = new ClientGroupNoteRepository();
        $this->lenderRepository = new LenderRepository();
        $this->userRepository = new UserRepository();
        $this->userDataRepository = new UserDataRepository();
        $this->userGroupLogRepository = new UserGroupLogRepository();
        $this->user = new User();
        $this->preferAppr = new PreferAppr();
        $this->excludeAppr = new ExcludeAppr();
        $this->userGroupRelation = new UserGroupRelation();

    }

    /**
     * Get Single Client Detail
     * @param  [type] $id [description]
     * @return bool or array
     */
    public function single($id)
    {
        $user = $this->model->where('id', $id)->first();

        if (!$user) {
            return false;
        }

        return $user;
    }


    /**
     * Create New Client.
     * @param  $params
     * @return bool
     */
    public function store($params)
    {
        return $this->model->create($params);
    }


    /**
     * Update Client Details
     * @param  [type] $id [description]
     * @param  [type] $data       [description]
     * @return [type]             [description]
     */
    public function update($id, $data)
    {
        $client = $this->model->where('id', $id)->first();

        if (isset($data['apis'])) {
            $client->apiUsers()->sync($data['apis']);
        }

        if (isset($data['prefer_appr'])) {
            $this->preferAppr->where('groupid', $id)->delete();
            $carbon = Carbon::now();
            $datetime = $carbon->toDateTimeString();
            $dataArr = [];
            foreach ($data['prefer_appr'] as $key => $value) {
                $dataArr[$key]['groupid'] = $id;
                $dataArr[$key]['apprid'] = $value;
                $dataArr[$key]['dts'] = $datetime;
            }
            $client->preferAppr()->insert($dataArr);

        } else {
            $this->preferAppr->where('groupid', $id)->delete();
        }


        if (isset($data['excluded_appr'])) {
            $this->excludeAppr->where('groupid', $id)->delete();
            $carbon = Carbon::now();
            $datetime = $carbon->toDateTimeString();
            $dataArr = [];
            foreach ($data['excluded_appr'] as $key => $value) {
                $dataArr[$key]['groupid'] = $id;
                $dataArr[$key]['apprid'] = $value;
                $dataArr[$key]['dts'] = $datetime;
            }
            $client->excludeAppr()->insert($dataArr);
        } else {
            $this->excludeAppr->where('groupid', $id)->delete();
        }

        if (isset($data['users_data'])) {
            $this->user->where('groupid', $id)
                ->update(['groupid' => 0]);

            $this->user->whereIn('id', $data['users_data'])
                ->update(['groupid' => $id]);
        } else {
            $this->user->where('groupid', $id)
                ->update(['groupid' => 0]);
        }

        if (isset($data['managers_data'])) {
            $this->userGroupRelation->where('group_id', $id)->delete();
            $dataArr = [];
            foreach ($data['managers_data'] as $key => $value) {
                $dataArr[$key]['user_id'] = $value;
                $dataArr[$key]['group_id'] = $id;
            }
            $client->userGroupRelations()->insert($dataArr);
        } else {
            $this->userGroupRelation->where('group_id', $id)->delete();
        }


        if (!$client) {
            return false;
        }

        return $client->update($data);
    }


    /**
     * Delete Client
     * @param  $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }


    /**
     * Get States
     * @return bool or array
     */
    public function getStates()
    {
        return $this->statesRepo->getStates();
    }


    /**
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function data($request)
    {
        if ($request->ajax()) {
            $whereSales = $request->sales ? [['user_data.user_id', '=', $request->sales]] : [];
            $whereState = $request->state ? [['user_groups.state', '=', $request->state]] : [];
            $client = $this->model->select(DB::raw('CONCAT(user_data.firstname, " ", user_data.lastname) AS salesperson'),
                'id', 'user_groups.descrip', 'user_groups.address1', 'user_groups.city', 'user_groups.state')
                ->LeftJoin('user_data', 'user_groups.salesid', '=', 'user_data.user_id')->where($whereState)->where($whereSales);
            return Datatables::of($client)
                ->addColumn('action', function ($client) {
                    return view('admin::management.client_settings.partials._options', ['row' => $client])
                        ->render();
                })

                ->rawColumns(['action'])
                ->make(true);
        }

    }

    /**
     * @return mixed
     */
    public function getSuperUsers()
    {
        $admins = $this->user
            ->select('user.id', 'user_data.firstname', 'user_data.lastname')
            ->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->where('user.user_type', '=', 1)
            ->whereIn('user.admin_priv', ['S'])
            ->where('user.active', '=', 'Y')
            ->orderBy('user_data.firstname')
            ->get();
        if ($admins) {
            foreach ($admins as $admin) {
                $lists[$admin->id] = trim($admin->firstname . ' ' . $admin->lastname);
            }
        }

        return $lists;
    }

    /**
     * @return mixed
     */
    public function getSales()
    {
        $sales = $this->user
            ->select('user.id', 'user_data.firstname', 'user_data.lastname')
            ->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->where('user.user_type', '=', 1)
            ->whereIn('user.admin_priv', ['O'])
            ->where('user.active', '=', 'Y')
            ->orderBy('user_data.firstname')
            ->get();
        if ($sales) {
            foreach ($sales as $sale) {
                $sales_list[$sale->id] = trim($sale->firstname . ' ' . $sale->lastname);
            }
        }
        return $sales_list;
    }


    public function uploadPdfFile($id, $request)
    {
        $data = $request->all();
        $file_upload_name = $data['file_upload_name'];
        if (!empty($request['file_upload_name']) && !empty($request->file('file'))) {
            if ($request->hasFile('file')) {
                $name = $request->file('file')->hashName();
                $fileSize = $request->file('file')->getClientSize();
                $save = Storage::disk('public')->putFile('user_group_files', $request->file('file'));
            }

            $mytime = new Carbon();
            $time = $mytime->toTimeString();
            $date = $mytime->toDateTimeString();
            $params = [
                'group_id' => $id,
                'docname' => $file_upload_name,
                'filename' => $name,
                'file_location' => $date,
                'created_at' => $time,
                'created_by' => admin()->id,
                'is_aws' => 1,
                'file_size' => $fileSize,
            ];
            $this->clientFileRepository->logCreateByFile($id);
            $this->clientFileRepository->store($params);
            return redirect()->back();
        }


    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile($id)
    {
        $file = $this->clientFileRepository->find($id);
        $fullPathToFile = storage_path() . "/app/public//user_group_files//" . $file->filename;
        if (file_exists($fullPathToFile)) {
            return response()->download($fullPathToFile);
        }
    }

    /**
     * @param $id
     */
    public function fileDelete($id)
    {
        $file = $this->clientFileRepository->find($id);
        $this->clientFileRepository->logCreateByFile($id);
        $file->delete();
    }

    public function searchOrders($data)
    {
        $groupId = $data['groupId'];
        $types = $data['types'];
        $dateFrom = date('Y-m-d 00:00:00', strtotime($data['dateFrom']));
        $dateTo = date('Y-m-d 23:59:59', strtotime($data['dateTo']));

        ini_set('memory_limit', '-1');
        $orders = $this->order
            ->leftJoin('user', 'user.id', '=', 'appr_order.orderedby')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'appr_order.groupid')
            ->whereNotIn('appr_order.status', [9])
            ->where('user_groups.id', '=', $groupId)
            ->whereBetween('appr_order.ordereddate', [$dateFrom, $dateTo])
            ->orderBy('appr_order.ordereddate', 'DESC');

        if ($types) {
            $orders = $orders->whereIn('appr_order.appr_type', $types);
        }

        $orders = $orders->get();

        return $orders;
    }


    /**
     *  Create log
     */
    public function logCreateGroup()
    {
        $this->model->created(function ($client) {
            $params =
                [
                    'group_id' => $client->id,
                    'created_date' => time(),
                    'created_by' => admin()->id,
                    'note' => '<span class="log_added">Group Created</span>'
                ];
            $this->clientLogRepository->store($params);
            $this->clientHistoryRepository->store($params);
        });


        $this->model->updated(function ($client) {
            $dirty = $client->getDirty();
            $changeFieldCount = count($dirty);
            $params =
                [
                    'group_id' => $client->id,
                    'created_date' => time(),
                    'created_by' => admin()->id,
                ];
            if (isset($dirty['active'])) {
                if ($dirty['active']) {
                    $params['note'] = '<span class="log_removed">Group Enabled</span>';
                } else {
                    $params['note'] = '<span class="log_removed">Group Disabled</span>';
                }
            } else {
                $params['note'] = '<span class="log_saved">Updated Group ' . $changeFieldCount . '  Changes Made.</span>';
            }
            $this->clientLogRepository->store($params);
        });
    }


    /**
     *  Create history
     */
    public function historyCreateGroup()
    {
        $this->model->updated(function ($client) {
            $dirty = $client->getDirty();
            $note = '';
            foreach ($dirty as $field => $newData) {
                $oldData = $client->getOriginal($field);

                $fieldName = str_replace('_', ' ', $field);
                $uppercaseName = ucwords($fieldName);
                $params = [
                    'group_id' => $client->id,
                    'created_date' => time(),
                    'created_by' => admin()->id,

                ];

                if ($field != 'show_loantype' && $field != 'show_loanpurpose' && $field != 'show_propertytype' && $field != 'show_apprtype' && $field != 'lenders_used') {
                    $note .= '<b>' . $uppercaseName . '</b>  Changed from <b>' . $oldData . '</b> to <b>' . $newData . '</b>.' . '<br>';
                } elseif ($field == 'show_loantype') {

                    $note = $this->noteLoanTypes($oldData, $newData, $note);

                } elseif ($field == 'show_loanpurpose') {

                    $note = $this->noteLoanPurpose($oldData, $newData, $note);

                } elseif ($field == 'show_propertytype') {

                    $note = $this->notePropertyTypes($oldData, $newData, $note);
                } elseif ($field == 'show_apprtype') {

                    $note = $this->noteAppraisalTypes($oldData, $newData, $note);
                } elseif ($field == 'lenders_used') {

                    $note = $this->noteLender($oldData, $newData, $note);
                }


                $params['note'] = $note;
            }
            $this->clientHistoryRepository->store($params);

        });

    }

    /**
     * @param $data
     * @return bool
     */
    public function changeActive($data)
    {
        $id = $data['id'];
        $activeData =
            [
                'active' => $data['active']
            ];
        return $this->update($id, $activeData);

    }


    /**
     * @param $oldData
     * @param $newData
     * @param $note
     * @return string
     */
    public function noteLoanTypes($oldData, $newData, $note)
    {
        $oldDataArray = explode(",", $oldData);
        $newDataArray = explode(',', $newData);
        $addData = array_diff($newDataArray, $oldDataArray);
        $removeData = array_diff($oldDataArray, $newDataArray);
        if (!empty($addData) && !empty($removeData)) {
            $addLoantype = $this->loanTypesRepository->loanTypesListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $removeLoantype = $this->loanTypesRepository->loanTypesListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Loan Types</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr
                . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        } elseif (!empty($addData)) {
            $addLoantype = $this->loanTypesRepository->loanTypesListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $note .= '-Limit <b>Loan Types</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr . '<br>';
        } elseif (!empty($removeData)) {
            $removeLoantype = $this->loanTypesRepository->loanTypesListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Loan Types</b> Changed.' . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        }

        return $note;
    }


    /**
     * @param $oldData
     * @param $newData
     * @param $note
     * @return string
     */
    public function noteLoanPurpose($oldData, $newData, $note)
    {
        $oldDataArray = explode(",", $oldData);
        $newDataArray = explode(',', $newData);
        $addData = array_diff($newDataArray, $oldDataArray);
        $removeData = array_diff($oldDataArray, $newDataArray);
        if (!empty($addData) && !empty($removeData)) {
            $addLoantype = $this->loanpurposeRepository->loanPurposeListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $removeLoantype = $this->loanpurposeRepository->loanPurposeListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Loan Purpose</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr
                . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        } elseif (!empty($addData)) {
            $addLoantype = $this->loanpurposeRepository->loanPurposeListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $note .= '-Limit <b>Loan Purpose</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr . '<br>';
        } elseif (!empty($removeData)) {
            $removeLoantype = $this->loanpurposeRepository->loanPurposeListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Loan Purpose</b> Changed.' . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        }
        return $note;
    }


    /**
     * @param $oldData
     * @param $newData
     * @param $note
     * @return string
     */
    public function notePropertyTypes($oldData, $newData, $note)
    {
        $oldDataArray = explode(",", $oldData);
        $newDataArray = explode(',', $newData);
        $addData = array_diff($newDataArray, $oldDataArray);
        $removeData = array_diff($oldDataArray, $newDataArray);
        if (!empty($addData) && !empty($removeData)) {
            $addLoantype = $this->propertyTypeRepository->propertyTypesListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $removeLoantype = $this->propertyTypeRepository->propertyTypesListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Property Types</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr
                . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        } elseif (!empty($addData)) {
            $addLoantype = $this->propertyTypeRepository->propertyTypesListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $note .= '-Limit <b>Property Types</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr . '<br>';
        } elseif (!empty($removeData)) {
            $removeLoantype = $this->propertyTypeRepository->propertyTypesListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Property Types</b> Changed.' . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        }
        return $note;
    }


    /**
     * @param $oldData
     * @param $newData
     * @param $note
     * @return string
     */
    public function noteAppraisalTypes($oldData, $newData, $note)
    {
        $oldDataArray = explode(",", $oldData);
        $newDataArray = explode(',', $newData);
        $addData = array_diff($newDataArray, $oldDataArray);
        $removeData = array_diff($oldDataArray, $newDataArray);
        if (!empty($addData) && !empty($removeData)) {
            $addLoantype = $this->apprTypeRepository->apprTypeListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $removeLoantype = $this->apprTypeRepository->apprTypeListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Appraisal Types</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr
                . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        } elseif (!empty($addData)) {
            $addLoantype = $this->apprTypeRepository->apprTypeListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $note .= '-Limit <b>Appraisal Types</b> Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr . '<br>';
        } elseif (!empty($removeData)) {
            $removeLoantype = $this->apprTypeRepository->apprTypeListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Limit <b>Appraisal Types</b> Changed.' . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        }
        return $note;
    }


    /**
     * @param $oldData
     * @param $newData
     * @param $note
     * @return string
     */
    public function noteLender($oldData, $newData, $note)
    {
        $oldDataArray = explode(",", $oldData);
        $newDataArray = explode(',', $newData);
        $addData = array_diff($newDataArray, $oldDataArray);
        $removeData = array_diff($oldDataArray, $newDataArray);
        if (!empty($addData) && !empty($removeData)) {
            $addLoantype = $this->lenderRepository->lesaleLendersListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $removeLoantype = $this->lenderRepository->lesaleLendersListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Watch List Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr
                . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        } elseif (!empty($addData)) {
            $addLoantype = $this->lenderRepository->lesaleLendersListAddRemoveFromClient($addData);
            $add = implode('=>', $addLoantype);
            $addStr = str_replace('=>', '<br>--- ', $add);
            $note .= '-Watch List Changed.' . '<br>' . 'Added' . '<br>--- ' . $addStr . '<br>';
        } elseif (!empty($removeData)) {
            $removeLoantype = $this->lenderRepository->lesaleLendersListAddRemoveFromClient($removeData);
            $remove = implode('=>', $removeLoantype);
            $removeStr = str_replace('=>', '<br>--- ', $remove);
            $note .= '-Watch List  Changed.' . '<br>' . 'Removed' . '<br>--- ' . $removeStr . '<br>';
        }
        return $note;
    }


    /**
     * @param $data
     * @return mixed
     */
    public function addNoteGroup($data)
    {
        if (!empty($data['user_note'])) {
            $groupId = $data['groupId'];
            $note = $data['user_note'];
            $carbon = Carbon::now();
            $datetime = $carbon->toDateTimeString();

            $params = [
                'groupid' => $groupId,
                'adminid' => admin()->id,
                'notes' => $note,
                'dts' => $datetime
            ];
            return $this->clientGroupNoteRepository->store($params);

        }

    }


    /**
     * @param $data
     * @return array
     */
    public function quickAddUser($data)
    {
        $groupId = $data['groupId'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $phone = $data['phone'];
        $ext = $data['ext'];
        $carbon = Carbon::now();
        $datetime = $carbon->toDateTimeString();

        $params = [
            'email' => $email,
            'password' => bcrypt('landmark'),
            'user_type' => 5,
            'groupid' => $groupId,
            'register_date' => $datetime,
            'active' => 'Y',
            'last_updated' => time(),

        ];

        $user = $this->userRepository->store($params);

        $group = $this->single($groupId);
        $paramsUserData = [
            'user_id' => $user->id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $phone,
            'phoneext' => $ext,
            'company' => $group->company,
            'comp_address' => $group->address1,
            'comp_address1' => $group->address2,
            'comp_city' => $group->city,
            'comp_state' => $group->state,
            'comp_zip' => $group->zip,
        ];

        $userData = $this->userDataRepository->store($paramsUserData);

        return $userUserData = [
            'user' => $user,
            'userData' => $userData
        ];
    }


    public function getUserGroupPreferredAppraisers($groupId)
    {
        return $this->preferAppr
            ->leftJoin('user', 'user.id', '=', 'prefer_appr.apprid')
            ->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->where('prefer_appr.groupid', '=', $groupId)
            ->orderBy(DB::raw('CONCAT(user_data.firstname, " ", user_data.lastname)'), 'ASC')
            ->get();
    }


    public function getUserGroupExcludedAppraisers($groupId)
    {
        return $this->excludeAppr
            ->leftJoin('user', 'user.id', '=', 'user_exclude.apprid')
            ->leftJoin('user_data', 'user.id', '=', 'user_exclude.apprid')
            ->where('user_exclude.groupid', '=', $groupId)
            ->orderBy(DB::raw('CONCAT(user_data.firstname, " ", user_data.lastname)'), 'ASC')
            ->get();

    }

    public function getUsersGroupsUsersInGroup($groupId)
    {
        return $this->user
            ->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->where('user.groupid', '=', $groupId)
            ->orderBy(DB::raw('CONCAT(user_data.firstname, " ", user_data.lastname)'), 'ASC')
            ->get();


    }


    public function getUsersGroupsManagers($groupId)
    {
        $group = $this->single($groupId);
        if (!$group) {
            return false;
        }
        $ids = array();
        $rows = $this->userGroupRelation
            ->select('user_id')->where('group_id', $groupId)
            ->get();
        if ($rows) {
            foreach ($rows as $row) {
                $ids[$row->user_id] = $row->user_id;
            }
        }

        if (!$ids) {
            return false;
        }

        return $this->user
            ->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->whereIn('user.id', $ids)
            ->orderBy(DB::raw('CONCAT(user_data.firstname, " ", user_data.lastname)'), 'ASC')
            ->get();

    }


    /**
     * @param $data
     * @return mixed
     */
    public function addApLog($data)
    {
        $orderIds = $data['orderIds'];
        $message = $data['message'];
        $groupId = $data['groupId'];

        if (!$groupId) {
            echo json_encode(array('error' => 'User ID or Group ID is missing.'));
            exit;
        }

        if (!$message) {
            echo json_encode(array('error' => 'Please enter a log message'));
            exit;
        }


        $ids = explode(',', $orderIds);
        $group = $this->single($groupId);

        if (!$groupId) {
            echo json_encode(array('error' => 'User ID or Group ID is missing.'));
            exit;
        }

        if (!$message) {
            echo json_encode(array('error' => 'Please enter a log message'));
            exit;
        }


        if ($ids && count($ids)) {
            foreach ($ids as $id) {
                if (!$id) {
                    continue;
                }
                $orderRow = $this->order::getApprOrderById($id);
                if (!$orderRow) {
                    echo json_encode(array('error' => "Sorry, But the Order ID specified " . $id . " does not exist. Please check that you entered it correctly."));
                    exit;
                }

                if ($orderRow->groupid != $groupId) {
                    echo json_encode(array('error' => "Sorry, But the Order ID specified " . $id . " is not associated with this user group."));
                    exit;
                }
            }
        }
        $params = [
            'groupid' => $groupId,
            'message' => $message,
            'created_date' => time(),
            'created_by' => admin()->id,
        ];
        $userGroup = $this->userGroupLogRepository->store($params);
        if ($ids && count($ids)) {
            $userGroup->orders()->attach($ids);
        }
        $userLogs = $this->userGroupLogRepository->getUserLogs($userGroup->id);
        return $userLogs;
    }


    /**
     * @param $request
     * @return array
     */
    public function searchAppraisers($input, $key, $groupId)
    {

        if ($groupId) {
            $users = array();
            if ($key == 'preferred') {
                $users = $this->getUserGroupPreferredAppraisers($groupId);
            } elseif ($key == 'excluded') {
                $users = $this->getUserGroupExcludedAppraisers($groupId);

            }
            if ($users && count($users)) {
                $_list = array();
                foreach ($users as $user) {
                    $_list[$user->id] = $user->id;
                }
            } else {
                $_list = [];
            }
        }

        $users = $this->user->whereNotIn('id', $_list)->where('user_type', 4)
            ->where(function ($query) use ($input) {
                $query->whereHas('userData', function ($q) use ($input) {
                    $q->where(\DB::raw('CONCAT(user_data.firstname, user_data.lastname)'), 'like', '%' . $input . '%');
                })->orwhere('email', 'LIKE', '%' . $input . '%');

            })->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->orderBy('user_data.firstname', 'asc', 'user_data.lastname', 'asc')
            ->take(15)->get();
        $dataArray = [
            'users' => $users,
        ];
        return $dataArray;
    }


    public function searchUsers($input, $key, $groupId)
    {

        if ($key == 'user-check') {

            $user = $this->userRepository->getUserById($input);
            if ($user['groupid']) {
                return array('error' => 'This user is belong a already group.');
            }

        }

        if ($groupId) {
            $users = array();
            if ($key == 'manager') {

                $users = $this->getUsersGroupsManagers($groupId);

            } elseif ($key == 'user') {
                $users = $this->getUsersGroupsUsersInGroup($groupId);

            }
            if ($users && count($users)) {
                $_list = array();
                foreach ($users as $user) {
                    $_list[$user->id] = $user->id;
                }
            } else {
                $_list = [];
            }
        }

        $users = $this->user->whereNotIn('id', $_list)->where('user_type', 5)
            ->where(function ($query) use ($input) {
                $query->whereHas('userData', function ($q) use ($input) {
                    $q->where(\DB::raw('CONCAT(user_data.firstname, user_data.lastname)'), 'like', '%' . $input . '%');
                })->orwhere('email', 'LIKE', '%' . $input . '%');

            })->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->orderBy('user_data.firstname', 'asc', 'user_data.lastname', 'asc')
            ->take(15)->get();
        $dataArray = [
            'users' => $users,
        ];
        return $dataArray;

    }


}
