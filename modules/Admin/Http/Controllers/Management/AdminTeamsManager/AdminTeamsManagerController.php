<?php

namespace Modules\Admin\Http\Controllers\Management\AdminTeamsManager;

use App\Models\Users\User;
use App\Models\Clients\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\Datatables\Datatables;
use App\Models\Geo\State;
use App\Models\Management\AdminTeamsManager\{ AdminTeamStatusSelectStaff, AdminTeamStatusSelectStatus, AdminTeamStatusSelectFlag, AdminTeamStatusSelectLoanType, AdminTeam, AdminTeamClient, AdminTeamMember, AdminTeamStates };
use App\Models\Appraisal\{ Status, AppraisalStatusSelect, LoanType };
use App\Models\Customizations\LoanType;
use Modules\Admin\Http\Requests\Management\AdminTeamsManager\AdminTeamsRequest;
use Exception;


class AdminTeamsManagerController extends AdminBaseController
{
    /**
     * Index page for Admin Teams
     * @return View
     */
    public function index()
    {
        return view('admin::management.admin_teams_manager.index');
    }

    /**
     * Create new Admin Teams
     * @return View
     */
    public function create()
    {
        $informationView = $this->informationView();
        $associationsView = $this->associationsView();
        $statusSelectView = $this->statusSelectView();

        return view('admin::management.admin_teams_manager.create_edit',
            compact(
                    'informationView',
                    'associationsView',
                    'statusSelectView'
                    )
            );
    }

    /**
     * Create new Admin Teams
     * @param AdminTeamsRequest $request
     * @return void
     */
    public function store(AdminTeamsRequest $request)
    {
        $inputs = $request->sortInputs();
        $insert = AdminTeam::saveAdminTeam(null, $inputs['info'], $inputs['relations']);

        $insert ? Session::flash('error', $insert) : Session::flash('success', 'Admin Team Successfully Updated.');
        return redirect()->route('admin.management.admin-teams-manager');
    }

    /**
     * Edit Admin Teams
     * @param $id
     * @return View
     */
    public function edit($id)
    {
        $adminTeam = AdminTeam::getAdminTeamById($id);
        $informationView = $this->informationView($id, $adminTeam);
        $associationsView = $this->associationsView($id, $adminTeam);
        $statusSelectView = $this->statusSelectView($id, $adminTeam);

        return view('admin::management.admin_teams_manager.create_edit',
            compact(
                    'adminTeam',
                    'informationView',
                    'associationsView',
                    'statusSelectView'
                    )
            );
    }

    /**
     * Update Admin Teams
     * @param AdminTeamsRequest $request
     * @param $id
     * @return void
     */
    public function update($id, AdminTeamsRequest $request)
    {
        $inputs = $request->sortInputs();
        $insert = AdminTeam::saveAdminTeam($id, $inputs['info'], $inputs['relations']);

        $insert ? Session::flash('error', $insert) : Session::flash('success', 'Admin Team Successfully Updated.');
        return redirect()->back();
    }

    /**
     * Delete Admin Teams
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        AdminTeam::where('id', $id)->delete();
        AdminTeam::deleteById($id);

        Session::flash('success', 'Admin Team Successfully Deleted.');
        return redirect()->back();
    }

    /**
    * informatinView
    * @param $id, $adminTeam
    * @return view
    */
    private function informationView($id = null, $adminTeam = null)
    {
        $adminTypes = AdminTeam::getAdminTeamTypes();

        return view('admin::management.admin_teams_manager.partials._information',
            compact(
                    'adminTypes',
                    'adminTeam'
                )
            );
    }

    /**
    * associationsView
    * @param $id, $adminTeam
    * @return view
    */
    private function associationsView($id = null, $adminTeam = null)
    {
        $states = State::all();
        $members = User::getAdminMembers();
        $clients = Client::getAllClients();
        $savedStates = is_null($id) ? $id : AdminTeamStates::getSelectedStates($id);
        $savedMembers = is_null($id) ? $id : AdminTeamMember::getSelectedMembers($id);
        $savedClients = is_null($id) ? $id : AdminTeamClient::getSelectedClients($id);

        return view('admin::management.admin_teams_manager.partials._associations',
            compact(
                    'states',
                    'members',
                    'clients',
                    'savedStates',
                    'savedMembers',
                    'savedClients'
                )
            );
    }

    /**
    * statusSelectView
    * @param $id, $adminTeam
    * @return view
    */
    private function statusSelectView($id = null, $adminTeam = null)
    {
        $members = User::getAdminMembers();
        $statuses = Status::getStatuses();
        $flags = AppraisalStatusSelect::$flags;
        $loanTypes = LoanType::getLoanTypes();

        $savedMembers = is_null($id) ? $id : AdminTeamStatusSelectStaff::getSelectedStaff($id);
        $savedStatuses = is_null($id) ? $id : AdminTeamStatusSelectStatus::getSelectedStatuses($id);
        $savedFlags = is_null($id) ? $id : AdminTeamStatusSelectFlag::getSelectedFlags($id);
        $savedLoanTypes = is_null($id) ? $id : AdminTeamStatusSelectLoanType::getSelectedLoanTypes($id);

        return view('admin::management.admin_teams_manager.partials._status_select',
            compact(
                    'adminTeam',
                    'members',
                    'statuses',
                    'flags',
                    'loanTypes',
                    'savedMembers',
                    'savedStatuses',
                    'savedFlags',
                    'savedLoanTypes'
                )
            );
    }

    /**
     * Gather data for index page and datatables
     */
    public function data()
    {
        $adminTeams = AdminTeam::allAdminTeams();
        return Datatables::of($adminTeams)
            ->editColumn('members', function ($r) {
                return AdminTeamMember::where('team_id', $r->id)->get()->count();
            })
            ->editColumn('clients', function ($r) {
                return AdminTeamClient::where('team_id', $r->id)->get()->count();
            })
            ->editColumn('states', function ($r) {
                return AdminTeamStates::where('team_id', $r->id)->get()->count();
            })
            ->editColumn('is_active', function ($r) {
                return ($r->is_active) ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($r) {
                return view('admin::management.admin_teams_manager.partials._options', ['row' => $r]);
            })
            ->make(true);
    }
}
