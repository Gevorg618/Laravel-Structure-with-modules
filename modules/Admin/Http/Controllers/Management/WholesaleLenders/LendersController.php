<?php

namespace Modules\Admin\Http\Controllers\Management\WholesaleLenders;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;
use Modules\Admin\Repositories\Management\WholesaleLenders\LendersRepository;
use Modules\Admin\Http\Requests\Management\WholesaleLenders\{UWContactInfoRequest, ProposedRequest, LenderRequest, ExcludedUsersRequest};
use App\Models\Clients\Client;

class LendersController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index(LendersRepository $lendersRepository)
    {
        $lenders = $lendersRepository->getLenders();
        return view('admin::management.wholesale_lenders.index', compact('lenders'));
    }

    /**
    * Create
    * @return view
    */
    public function create(LendersRepository $lendersRepository)
    {
        $listData = $lendersRepository->getUserGroupLeadsSaleRepDropdown();
        $states = getStates();
        $userGroups = Client::getAllClients();
        $isAdmin = isAdmin();
        return view('admin::management.wholesale_lenders.create',
                    compact(
                        'states',
                        'userGroups',
                        'listData',
                        'isAdmin'
                    )
                );
    }

    /**
    * Store
    * @return view
    */
    public function store(
            LenderRequest $request,
            LendersRepository $lendersRepository
        )
    {
        $inputs = $request->all();
        $insert = $lendersRepository->store($inputs);
        if ($insert) {
            Session::flash('success', 'Lender Successfully Saved');
            return redirect(route('admin.management.lenders'));
        }
        Session::flash('error', 'Sorry we could not save the record.');
        return redirect()->back();
    }

    /**
    * Edit
    * @return view
    */
    public function edit($id, LendersRepository $lendersRepository)
    {
        $lender = $lendersRepository->getLenderById($id);
        $selectedClients = $lendersRepository->getSelectedClients($lender->id);
        $selectedClientsNames = $lendersRepository->getLenderClientsTitles($lender->id);
        $selectedStates = $lendersRepository->getSelectedStates($lender->id);
        $lenderUserManagers = $lendersRepository->getLenderUserManagers($lender->id);
        $lenderExcludedAppraisers = $lendersRepository->getLenderExcludedAppraisers($lender->id);
        $notes = $lendersRepository->getLenderNotes($lender->id);
        $proposeds = $lendersRepository->getLenderProposedloans($lender->id);
        $listData = $lendersRepository->getUserGroupLeadsSaleRepDropdown();
        $states = getStates();
        $userGroups = Client::getAllClients();
        $isAdmin = isAdmin();
        $uws = $lendersRepository->getAllUWByLenderId($lender->id);

        return view('admin::management.wholesale_lenders.edit',
                    compact(
                        'states',
                        'userGroups',
                        'listData',
                        'isAdmin',
                        'lender',
                        'selectedClients',
                        'selectedStates',
                        'lenderUserManagers',
                        'lenderExcludedAppraisers',
                        'selectedClientsNames',
                        'notes',
                        'proposeds',
                        'uws'
                    )
                );
    }

    /**
    * Edit
    * @return view
    */
    public function update(
            $id,
            LenderRequest $request,
            LendersRepository $lendersRepository
        )
    {
        $inputs = $request->all();
        $update = $lendersRepository->update($id, $inputs);
        if ($update) {
            Session::flash('success', 'Lender Successfully Saved');
            return redirect(route('admin.management.lenders'));
        }
        Session::flash('error', 'Sorry we could not save the record.');
        return redirect()->back();
    }

    /**
    * Delete
    * @return void
    */
    public function delete($id, LendersRepository $lendersRepository)
    {
        $delete = $lendersRepository->delete($id);
        return response()->json($delete, 200);
    }

    /**
    * Add Proposed
    * @return view
    */
    public function addProposed($id, LendersRepository $lendersRepository)
    {
        $lender = $lendersRepository->getLenderById($id);
        $states = getStates();
        $userGroups = Client::getAllClients();
        $apprTypes = $lendersRepository->getApprTypeList();
        $addendas = $lendersRepository->getAddendaRecords();
        return view('admin::management.wholesale_lenders.partials.proposed._create',
                    compact(
                        'lender',
                        'states',
                        'userGroups',
                        'apprTypes',
                        'addendas'
                    )
                );
    }

    /**
     * Create Proposed
     * @return void
     */
    public function createProposed(
            ProposedRequest $request,
            LendersRepository $lendersRepository
        )
    {
        $inputs = $request->all();
        $insert = $lendersRepository->createProposed($inputs);
        if ($insert) {
            Session::flash('success', 'Proposed Loan Saved');
            return redirect(route('admin.management.lenders.edit', ['id' => $inputs['lender_id']]));
        }
        Session::flash('error', 'Sorry we could not save the record.');
        return redirect()->back();
    }

    /**
    * Edit Proposed
    * @return view
    */
    public function editProposed(
            $id,
            LendersRepository $lendersRepository
        )
    {
        $proposed = $lendersRepository->getProposedById($id);
        $selectedApprTypes = $lendersRepository->getSelectedApprTypes($id);
        $selectedStates = $lendersRepository->getProposedSelectedStates($id);
        $selectedAddendas = $lendersRepository->getSelectedAddendas($id);
        $lender = $lendersRepository->getLenderById($proposed->lender_id);
        $states = getStates();
        $userGroups = Client::getAllClients();
        $apprTypes = $lendersRepository->getApprTypeList();
        $addendas = $lendersRepository->getAddendaRecords();
        return view('admin::management.wholesale_lenders.partials.proposed._edit',
                    compact(
                        'lender',
                        'states',
                        'userGroups',
                        'apprTypes',
                        'addendas',
                        'proposed',
                        'selectedApprTypes',
                        'selectedStates',
                        'selectedAddendas'
                    )
                );
    }

    /**
    * Edit Proposed
    * @return void
    */
    public function updateProposed(
            ProposedRequest $request,
            LendersRepository $lendersRepository
        )
    {
        $inputs = $request->all();
        $update = $lendersRepository->updateProposed($inputs);
        if ($update) {
            Session::flash('success', 'Proposed Loan Saved');
            return redirect(route('admin.management.lenders.edit', ['id' => $inputs['lender_id']]));
        }
        Session::flash('error', 'Sorry we could not save the record.');
        return redirect()->back();
    }

    /**
     * Get data for datatable
     */
    public function data(LendersRepository $lendersRepository)
    {
        $lenders = $lendersRepository->getWholesaleLenders();

        return Datatables::of($lenders)
            ->addColumn('checkbox', function ($r) {
                return view('admin::management.wholesale_lenders.partials.table._checkbox', ['row' => $r]);
            })
            ->editColumn('address', function($r) {
                return trim($r->lender_address1 . ' ' . $r->lender_address2);
            })
            ->editColumn('send_email', function($r) {
                return view('admin::management.wholesale_lenders.partials.table._send_email', ['row' => $r]);
            })
            ->editColumn('clients', function($r) use ($lendersRepository) {
                $clients = $lendersRepository->clients($r->id);
                $list_clients = [];
                if($clients && count($clients)) {
                    foreach($clients as $row) {
                        $list_clients[$row->groupid] = $row->descrip;
                    }
                }
                $r->clients = $list_clients;
                return view('admin::management.wholesale_lenders.partials.table._clients', ['row' => $r]);
            })
            ->editColumn('states', function($r) use ($lendersRepository) {
                $states = $lendersRepository->states($r->id);
                $list_states = [];
                if($states && count($states)) {
                    foreach($states as $row) {

                        $list_states[$row->groupid] = $row->state;
                    }
                }
                $r->states = $list_states;
                return view('admin::management.wholesale_lenders.partials.table._states', ['row' => $r]);
            })
            ->addColumn('action', function ($r) {
                return view('admin::management.wholesale_lenders.partials.table._options', ['row' => $r]);
            })
            ->make(true);
    }

    /**
    * Get Client Names By Search
    * @return json
    */
    public function getClientNames(Request $request, LendersRepository $lendersRepository)
    {
        $inputs = $request->term;
        $row = $lendersRepository->getClientNames($inputs);
        return $row;
    }

    /**
    * Get Appraiser Names By Search
    * @return json
    */
    public function deleteProposed(Request $request, LendersRepository $lendersRepository)
    {
        $id = $request->proposed_id;
        $row = $lendersRepository->deleteProposed($id);
        return redirect(route('admin.management.lenders.edit', ['id' => $request->lender_id]));
    }

    /**
    * Get Appraiser Names By Search
    * @return json
    */
    public function getAppraiserNames(Request $request, LendersRepository $lendersRepository)
    {
        $inputs = $request->term;
        $row = $lendersRepository->getAppraiserNames($inputs);
        return $row;
    }

    /**
    * Add User Manager
    * @return json
    */
    public function addUserManager(Request $request, LendersRepository $lendersRepository)
    {
        $inputs = $request->all();
        $row = $lendersRepository->addUserManager($inputs);
        return $row;
    }

    /**
    * Add Excluded Appraiser
    * @return json
    */
    public function addExcludedAppraiser(Request $request, LendersRepository $lendersRepository)
    {
        $inputs = $request->all();
        $row = $lendersRepository->addExcludedAppraiser($inputs);
        return $row;
    }

    /**
    * Delete User Manager
    * @return json
    */
    public function deleteUserManager(Request $request, LendersRepository $lendersRepository)
    {
        $inputs = $request->all();
        $row = $lendersRepository->deleteUserManager($inputs);
        return $row;
    }

    /**
    * Delete Excluded Appraiser
    * @return json
    */
    public function deleteExcludedAppraiser(Request $request, LendersRepository $lendersRepository)
    {
        $inputs = $request->all();
        $row = $lendersRepository->deleteExcludedAppraiser($inputs);
        return $row;
    }

    /**
    * Add UW Contact Info
    * @return json
    */
    public function addUW(
                UWContactInfoRequest $request,
                LendersRepository $lendersRepository
            )
    {
        $inputs = $request->all();
        $insert = $lendersRepository->addUW($inputs);
        return response()->json(['data' => $insert, 'message' => 'success']);
    }

    /**
    * Add UW Contact Info
    * @return json
    */
    public function addUserNote(
                Request $request,
                LendersRepository $lendersRepository
            )
    {
        $inputs = $request->all();
        $note = $lendersRepository->addUserNote($inputs);
        return response()->json(['data' => $note, 'message' => 'success']);
    }

    /**
    * Update UW Contact Info
    * @return json
    */
    public function updateUW(
                $id,
                UWContactInfoRequest $request,
                LendersRepository $lendersRepository
            )
    {
        $inputs = $request->all();
        $insert = $lendersRepository->updateUW($id, $inputs);
        return response()->json(['data' => $insert, 'message' => 'success']);
    }


    /**
    * Delete UW Contact Info
    * @return json
    */
    public function deleteUW(
                $id,
                LendersRepository $lendersRepository
            )
    {
        $insert = $lendersRepository->deleteUW($id);
        return response()->json(['data' => $insert, 'message' => 'success']);
    }

    /**
    * import Excluded Users
    * @return view
    */
    public function importExcludedUsers(
            ExcludedUsersRequest $request,
            LendersRepository $lendersRepository
        )
    {
        $inputs = $request->all();
        $insert = $lendersRepository->importExcludedUsers($inputs);
        if (isset($insert['success'])) {
            Session::flash('success', $insert['success']);
            return redirect()->back();
        }
        Session::flash('error', $insert['error']);
        return redirect()->back();
    }

    /**
    * download template
    * @return file
    */
    public function downloadTemplate(LendersRepository $lendersRepository)
    {
        $template = "";
        $headers = [];
        $rows = [];
        $sep = ",";
        $data = [];

        // Headers
        $headers = ['FIRSTNAME', 'LASTNAME', 'STATE', 'ZIP', 'LICENSE_STATE', 'LICENSE_NUMBER', 'EMAIL'];
        $headersCount = count($headers);

        // Create template
        $data[] = implode($sep, $headers);
        $template = implode("\n", $data);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=lenders_exclude_template.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $template;
        exit;
    }
}
