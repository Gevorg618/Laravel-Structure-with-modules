<?php

namespace Modules\Admin\Repositories\Management\WholesaleLenders;

use App\Models\Management\WholesaleLenders\{
    ExcludedLicenses,
    UserGroupLender,
    UserGroupLenderUwContactInfo,
    UserGroupLenderStateRel,
    UserGroupLenderRel,
    UserGroupLenderUserManager,
    UserGroupLenderExcludeAppraiser,
    UserGroupLenderNote,
    UserGroupLenderProposedLoanAmount,
    UserGroupLenderProposedLoanAmountApprTypeRel,
    UserGroupLenderProposedLoanAmountStateRel,
    UserGroupLenderProposedLoanAmountAddendaRel
};
use DB, Session;
use App\Models\Users\User;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;
use App\Models\Customizations\Addenda;

class LendersRepository
{
    private $lenders;
    private $user;
    private $clients;
    private $userManager;
    private $appr;
    private $notes;
    private $proposed;
    private $typeRel;
    private $stateRel;
    private $addendaRel;
    private $apprType;
    private $addenda;

    /**
     * LendersRepository constructor.
     */
    public function __construct()
    {
        $this->lenders = new UserGroupLender();
        $this->user = new User();
        $this->clients = new UserGroupLenderRel();
        $this->userManager = new UserGroupLenderUserManager();
        $this->appr = new UserGroupLenderExcludeAppraiser();
        $this->notes = new UserGroupLenderNote();
        $this->proposed = new UserGroupLenderProposedLoanAmount();
        $this->typeRel = new UserGroupLenderProposedLoanAmountApprTypeRel();
        $this->stateRel = new UserGroupLenderProposedLoanAmountStateRel();
        $this->addendaRel = new UserGroupLenderProposedLoanAmountAddendaRel();
        $this->apprType = new Type();
        $this->addenda = new Addenda();
    }

    /**
     * get Lender By Id
     * @return collection
     */
    public function getLenderById($id)
    {
        return $this->lenders->where('id', $id)->first();
    }

    /**
     * Get Wholesale Lenders for select
     * @return collection
     */
    public function getLenders()
    {
        return $this->lenders->select('id', 'lender')->orderBy('lender', 'ASC')->get();
    }

    /**
     * Get Wholesale Lenders
     * @return collection
     */
    public function getWholesaleLenders()
    {
        $query = $this->lenders->select('user_group_lender.*', 'c.clients_total', 's.states_total')
                            ->leftJoin(\DB::raw('(SELECT lenderid, count(groupid) as clients_total FROM user_group_lender_rel GROUP  BY lenderid) as c'), 'c.lenderid', '=', 'user_group_lender.id')
                            ->leftJoin(\DB::raw('(SELECT lenderid, count(state) as states_total FROM user_group_lender_state_rel GROUP  BY lenderid) as s'), 's.lenderid', '=', 'user_group_lender.id');
        return  $query->orderBy('lender', 'ASC')->get();
    }

    /**
     * Get Wholesale Lenders Clients
     * @return collection
     */
    public function clients($id)
    {
        return $this->clients->select('g.descrip', 'user_group_lender_rel.groupid')
                        ->leftJoin('user_groups as g', 'g.id', '=', 'user_group_lender_rel.groupid')
                        ->where('user_group_lender_rel.lenderid', $id)->get();
    }

    /**
     * Get Selected Clients
     * @return collection
     */
    public function getSelectedClients($id)
    {
        return $this->clients->where('lenderid', $id)->get();
    }

    /**
     * Get Wholesale Lenders States
     * @return collection
     */
    public function states($id)
    {
        return UserGroupLenderStateRel::select('state')->where('lenderid', $id)->get();
    }

    /**
     * Get Selected States
     * @return collection
     */
    public function getSelectedStates($id)
    {
        return UserGroupLenderStateRel::where('lenderid', $id)->get();
    }

    /**
     * Get Sales and Admins
     * @return collection
     */
    public function getUserGroupLeadsSaleRepDropdown()
    {
        $list = [];
        $sales = $this->user
                    ->select('user.id', 'o.firstname', 'o.lastname')
                    ->leftJoin('user_data as o', 'user.id', '=', 'o.user_id')
                    ->where('user.user_type', '=', 1)
                    ->whereIn('user.admin_priv', ['O'])
                    ->where('user.active', '=', 'Y')
                    ->orderBy('o.firstname')->get();

        $admins = $this->user
                    ->select('user.id', 'o.firstname', 'o.lastname')
                    ->leftJoin('user_data as o', 'user.id', '=', 'o.user_id')
                    ->where('user.user_type', '=', 1)
                    ->whereIn('user.admin_priv', ['S'])
                    ->where('user.active', '=', 'Y')
                    ->orderBy('o.firstname')->get();
        if($sales) {
            foreach($sales as $sale) {
                $list['Sales'][$sale->id] = trim($sale->firstname . ' ' . $sale->lastname);
            }
        }

        if($admins) {
            foreach($admins as $admin) {
                $list['Super Users'][$admin->id] = trim($admin->firstname . ' ' . $admin->lastname);
            }
        }
        return $list;
    }

    /**
     * Get Lender User Managers
     * @return collection
     */
    public function getLenderUserManagers($id)
    {
        return $this->userManager
                ->select('u.id', 'u.email', 'o.firstname', 'o.lastname')
                ->leftJoin('user as u', 'u.id', '=', 'user_group_lender_user_manager.userid')
                ->leftJoin('user_data as o', 'u.id', '=', 'o.user_id')
                ->where('user_group_lender_user_manager.lenderid', $id)
                ->orderBy('o.firstname', 'ASC')->get();
    }

    /**
     * Get Lender Excluded Appraisers
     * @return collection
     */
    public function getLenderExcludedAppraisers($id)
    {
        return $this->appr
                ->select('u.id', 'u.email', 'o.firstname', 'o.lastname')
                ->leftJoin('user as u', 'u.id', '=', 'user_group_lender_exclude_appraiser.userid')
                ->leftJoin('user_data as o', 'u.id', '=', 'o.user_id')
                ->where('user_group_lender_exclude_appraiser.lenderid', $id)
                ->orderBy('o.firstname', 'ASC')->get();
    }

    /**
     * Get Client Names By Search
     * @return collection
     */
    public function getClientNames($term)
    {
        $items = [];

        if ($term) {
            $rows = $this->user
                ->select('user.id', 'user.email', 'o.firstname', 'o.lastname', 'user.user_type')
                ->leftJoin('user_data as o', 'user.id', '=', 'o.user_id')
                ->where(\DB::raw("(LOWER(user.email) LIKE '%".str_replace("'", "\'", $term)."%' OR LOWER(CONCAT(o.firstname,' ',o.lastname)) LIKE '%".str_replace("'", "\'", $term)."%') AND user.user_type"), 5)
                ->orderBy('o.firstname', 'ASC')->limit(20)->get();
            if ($rows) {
                foreach ($rows as $r) {
                    $name = sprintf("%s (%s)", ucwords(strtolower(trim($r->firstname . ' ' . $r->lastname))), $r->email);
                    $items[] = array('label' => $name, 'value' => $r->email, 'id' => $r->id);
                }
            }
        }
        return json_encode($items);
    }

    /**
     * Get Appraiser Names By Search
     * @return collection
     */
    public function getAppraiserNames($term)
    {
        $items = [];

        if ($term) {
            $rows = $this->user
                ->select('user.id', 'user.email', 'o.firstname', 'o.lastname')
                ->leftJoin('user_data as o', 'user.id', '=', 'o.user_id')
                ->where(\DB::raw("(LOWER(user.email) LIKE '%".str_replace("'", "\'", $term)."%' OR LOWER(CONCAT(o.firstname,' ',o.lastname)) LIKE '%".str_replace("'", "\'", $term)."%') AND user.user_type"), 4)
                ->orderBy('o.firstname', 'ASC')->limit(20)->get();
            if ($rows) {
                foreach ($rows as $r) {
                    $name = sprintf("%s (%s)", ucwords(strtolower(trim($r->firstname . ' ' . $r->lastname))), $r->email);
                    $items[] = array('label' => $name, 'value' => $r->email, 'id' => $r->id);
                }
            }
        }
        return json_encode($items);
    }

    /**
     * Get Selected Clients Names
     * @return collection
     */
    public function getLenderClientsTitles($id)
    {
        return $this->clients
                ->select('g.descrip', 'user_group_lender_rel.groupid')
                ->leftJoin('user_groups as g', 'g.id', '=', 'user_group_lender_rel.groupid')
                ->where('user_group_lender_rel.lenderid', $id)->get();
    }

    /**
     * Add User Manager
     * @return collection
     */
    public function addUserManager($inputs)
    {
        $lenderId = $inputs['lenderId'];
        $userId = $inputs['userId'];

        // Check it does not exists already
        $exists = $this->userManager
                    ->where('lenderid', $lenderId)
                    ->where('userid', $userId)
                    ->first();
        if ($exists) {
            return json_encode(['error' => 'User is already a manager under this lender.']);
        }

        // Check if the user is already a wholesale lender manager
        $exists = $this->userManager
                    ->where('userid', $userId)
                    ->first();
        if ($exists) {
            return json_encode(['error' => 'User is already a manager under a different whole sale lender.']);
        }

        // Add
        $row = $this->userManager->create([
                'lenderid' => $lenderId,
                'userid' => $userId
            ]);
        $users = $this->getLenderUserManagers($row->lenderid);

        if ($users->contains('id', $userId)) {
            $user = $users->where('id', $userId)->first();
            $row->name = ucwords(strtolower(trim($user->firstname . ' ' . $user->lastname)));
            $row->email = $user->email;
        }

        return json_encode(['data' => $row]);
    }

    /**
     * Add Excluded Appraiser
     * @return collection
     */
    public function addExcludedAppraiser($inputs)
    {
        $lenderId = $inputs['lenderId'];
        $userId = $inputs['userId'];

        // Check it does not exists already
        $exists = $this->appr
                    ->where('lenderid', $lenderId)
                    ->where('userid', $userId)
                    ->first();
        if ($exists) {
            return json_encode(['error' => 'Appraiser is already in the exluded list for this lender.']);
        }

        // Add
        $row = $this->appr->create([
                'lenderid' => $lenderId,
                'userid' => $userId,
                'created_date' => time()
            ]);
        $users = $this->getLenderExcludedAppraisers($row->lenderid);

        if ($users->contains('id', $userId)) {
            $user = $users->where('id', $userId)->first();
            $row->name = ucwords(strtolower(trim($user->firstname . ' ' . $user->lastname)));
            $row->email = $user->email;
        }

        return json_encode(['data' => $row]);
    }

    /**
     * Delete User Manager
     * @return collection
     */
    public function deleteUserManager($inputs)
    {
        $lenderId = $inputs['lenderId'];
        $userId = $inputs['userId'];

        // Check it does not exists already
        $exists = $this->userManager
                    ->where('lenderid', $lenderId)
                    ->where('userid', $userId)
                    ->first();
        if (!$exists) {
            return json_encode(['error' => 'User and lender combination was not found.']);
        }

        // delete
        $this->userManager
                ->where('lenderid', $lenderId)
                ->where('userid', $userId)
                ->delete();

        return json_encode(['id' => $lenderId]);
    }

    /**
     * Delete Excluded Appraiser
     * @return collection
     */
    public function deleteExcludedAppraiser($inputs)
    {
        $lenderId = $inputs['lenderId'];
        $userId = $inputs['userId'];

        // Check it does not exists already
        $exists = $this->appr
                    ->where('lenderid', $lenderId)
                    ->where('userid', $userId)
                    ->first();
        if (!$exists) {
            return json_encode(['error' => 'User and lender combination was not found.']);
        }

        // delete
        $this->appr
                ->where('lenderid', $lenderId)
                ->where('userid', $userId)
                ->delete();

                return json_encode(['id' => $lenderId]);
    }

    /**
     * Get Lender Notes
     * @return collection
     */
    public function getLenderNotes($id)
    {
        return $this->notes->where('lenderid', $id)->orderBy('dts', 'ASC')->get();
    }

    /**
     * Get Lender Proposed Loans
     * @return collection
     */
    public function getLenderProposedloans($lenderId)
    {
        $rows = $this->proposed->where('lender_id', $lenderId)->orderBy('title', 'ASC')->get();

        foreach ($rows as $row) {
            $row->types = $this->getSelectedProposedLoanApprTypes($row->id);
            $row->states = $this->getSelectedProposedLoanStates($row->id);
            $row->addendas = $this->getSelectedProposedAddendas($row->id);
        }

        return $rows;
    }

    /**
     * Get Selected Proposed Loan Appr Types Count
     * @return collection
     */
    public function getSelectedProposedLoanApprTypes($id)
    {
        return $this->typeRel->where('proposed_id', $id)->count();
    }

    /**
     * Get Selected Proposed Loan States Count
     * @return collection
     */
    function getSelectedProposedLoanStates($id)
    {
        return $this->stateRel->where('proposed_id', $id)->count();
    }

    /**
     * Get Selected Proposed Addendas Count
     * @return collection
     */
    function getSelectedProposedAddendas($id)
    {
        return $this->addendaRel->where('proposed_id', $id)->count();
    }

    /**
     * Get appraisal type
     * @return collection
     */
    public function getApprTypeList()
    {
        $rows = $this->apprType->orderBy(\DB::raw("CONCAT(form,'',descrip)"), 'ASC')->get();
        foreach($rows as $row) {
            $row->description = $row->form ? ($row->form . ' - ' . $row->descrip) : $row->descrip;
        }
        return $rows;
    }

    /**
     * Get Addenda Records
     * @return collection
     */
    public function getAddendaRecords()
    {
        return $this->addenda->select('id', 'descrip')->orderBy('descrip', 'ASC')->get();
    }

     /**
     * Create Proposed
     * @return collection
     */
    public function createProposed($inputs)
    {
        $lenderId = $inputs['lender_id'];
        $lender = $this->getLenderById($lenderId);
        $title = $inputs['title'];
        $rangeStart = $inputs['range_start'];
        $rangeEnd = $inputs['range_end'];
        $amount = $inputs['amount'];
        $appraisalTypes = isset($inputs['appraisalTypes']) ? $inputs['appraisalTypes'] : null;
        $states = isset($inputs['states']) ? $inputs['states'] : null;
        $addendas = isset($inputs['addendas']) ? $inputs['addendas'] : null;

        try {
            DB::beginTransaction();

            $row = $this->proposed->create([
                'lender_id' => $lender->id,
                'title' => $title,
                'range_start' => $rangeStart,
                'range_end' => $rangeEnd,
                'amount' => $amount,
            ]);

            if (!is_null($appraisalTypes)) {
                foreach ($appraisalTypes as $type) {
                    $this->typeRel->create([
                        'proposed_id' => $row->id,
                        'appr_type_id' => $type
                    ]);
                }
            }

            if (!is_null($states)) {
                foreach ($states as $state) {
                    $this->stateRel->create([
                        'proposed_id' => $row->id,
                        'state' => $state
                    ]);
                }
            }

            if (!is_null($addendas)) {
                foreach ($addendas as $addenda) {
                    $this->addendaRel->create([
                        'proposed_id' => $row->id,
                        'addenda_id' => $addenda
                    ]);
                }
            }

            DB::commit();
            return true;
        }catch(\Execption $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get Proposed By Id
     * @return collection
     */
    public function getProposedById($id)
    {
        return $this->proposed->where('id', $id)->first();
    }

    /**
     * Get Selected Appr Types
     * @return collection
     */
    public function getSelectedApprTypes($id)
    {
        return $this->typeRel->where('proposed_id', $id)->get();
    }

    /**
     * Get Selected States
     * @return collection
     */
    public function getProposedSelectedStates($id)
    {
        return $this->stateRel->where('proposed_id', $id)->get();
    }

    /**
     * Get Selected Addendas
     * @return collection
     */
    public function getSelectedAddendas($id)
    {
        return $this->addendaRel->where('proposed_id', $id)->get();
    }

    /**
     * Update Proposed
     * @return collection
     */
    public function updateProposed($inputs)
    {
        $id = $inputs['proposed_id'];
        $lenderId = $inputs['lender_id'];
        $title = $inputs['title'];
        $rangeStart = $inputs['range_start'];
        $rangeEnd = $inputs['range_end'];
        $amount = $inputs['amount'];
        $appraisalTypes = isset($inputs['appraisalTypes']) ? $inputs['appraisalTypes'] : null;
        $states = isset($inputs['states']) ? $inputs['states'] : null;
        $addendas = isset($inputs['addendas']) ? $inputs['addendas'] : null;

        try {
            DB::beginTransaction();

            $this->proposed->where('id', $id)->update([
                'lender_id' => $lenderId,
                'title' => $title,
                'range_start' => $rangeStart,
                'range_end' => $rangeEnd,
                'amount' => $amount,
            ]);

            $this->typeRel->where('proposed_id', $id)->delete();
            $this->stateRel->where('proposed_id', $id)->delete();
            $this->addendaRel->where('proposed_id', $id)->delete();

            if (!is_null($appraisalTypes)) {
                foreach ($appraisalTypes as $type) {
                    $this->typeRel->create([
                        'proposed_id' => $id,
                        'appr_type_id' => $type
                    ]);
                }
            }

            if (!is_null($states)) {
                foreach ($states as $state) {
                    $this->stateRel->create([
                        'proposed_id' => $id,
                        'state' => $state
                    ]);
                }
            }

            if (!is_null($addendas)) {
                foreach ($addendas as $addenda) {
                    $this->addendaRel->create([
                        'proposed_id' => $id,
                        'addenda_id' => $addenda
                    ]);
                }
            }

            DB::commit();
            return true;
        }catch(\Execption $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
    * Get All UW By Lender Id
    * @return collection
    */
    public function getAllUWByLenderId($id)
    {
        return UserGroupLenderUwContactInfo::where('lenderid', $id)->get();
    }

    /**
    * delete Proposed
    * @return bool
    */
    public function deleteProposed($id)
    {
        return $this->proposed->where('id', $id)->delete();
    }

    /**
    * add User Note
    * @return collection
    */
    public function addUserNote($inputs)
    {
        $id = $inputs['id'];
        $note = $inputs['note'];

        // Add note
        $row = $this->notes->create([
            'lenderid' => $id,
            'adminid' => getUserId(),
            'notes' => $note,
            'dts' => date('Y-m-d H:i:s')
        ]);
        $row->dts = date('m/d/Y H:i', strtotime($row->dts));
        $row->adminid = getUserFullNameById($row->adminid);
        $row->count = $this->notes->where('lenderid', $id)->count();
        return $row;
    }

    /**
    * add UW Contact Info
    * @return collection
    */
    public function addUW($inputs)
    {
        $lenderid = !is_null($inputs["lenderid"]) ? (int)$inputs["lenderid"] : 0;
        $row = UserGroupLenderUwContactInfo::create([
            'lenderid' => $lenderid,
            'created_by' => getUserId(),
            'full_name' => $inputs["uw_fullname"],
            'email' => $inputs["email"],
            "phone" => $this->formatPhone($inputs["uw_phone"]),
        ]);
        return UserGroupLenderUwContactInfo::where('id', $row->id)->first();
    }

    /**
    * update UW Contact Info
    * @return collection
    */
    public function updateUW($id, $inputs)
    {
        UserGroupLenderUwContactInfo::where('id', $id)->update([
            'updated_by' => getUserId(),
            'full_name' => $inputs["uw_fullname"],
            'email' => $inputs["email"],
            "phone" => $this->formatPhone($inputs["uw_phone"]),
            "updated_at" => date('Y-m-d h:i:s')
        ]);
        return UserGroupLenderUwContactInfo::where('id', $id)->first();
    }

    /**
    * Delete UW Contact Info
    * @return collection
    */
    public function deleteUW($id)
    {
        return UserGroupLenderUwContactInfo::where('id', $id)->delete();
    }

    public function formatPhone($t)
    {
        $t = str_replace(array('(', ')', '-', ' ', '.'), '', $t);
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/","$1-$2-$3", $t);
    }

    /**
    * Update Lender
    * @return void
    */
    public function store($inputs)
    {
        // Init
        $lender = trim($inputs['lender']);
        $lenderDropdownTitle = trim($inputs['lender_dropdown_title']);
        $lender_address1 = $inputs['lender_address1'];
        $lender_address2 = $inputs['lender_address2'];
        $lender_city = $inputs['lender_city'];
        $lender_state = $inputs['lender_state'];
        $lender_zip = $inputs['lender_zip'];
        $clients = $inputs['clients'];
        $states = isset($inputs['states']) ? $inputs['states'] : 0;
        $autoselect = (int)$inputs['enable_auto_select'];
        $comments = $inputs['comments'];
        $finalEmail = $inputs['finalborroweremail'];
        $finalMail = $inputs['finalborrowerpostal'];

        $enableAVM = $inputs['enable_avm'];
        $avmRequirePayment = $inputs['avm_require_payment'];
        $avmFee = $inputs['avm_fee'];

        $enableDocuVault = $inputs['enable_docuvault'];
        $docuVaultRequirePayment = $inputs['docuvault_require_payment'];
        $docuVaultFee = $inputs['docuvault_fee'];
        $docuVaultMailFee = $inputs['mail_appr_addfee'];
        $isProposedLoan = $inputs['is_proposed_loan_amount'];

        $admin_notes = $inputs['admin_notes'];
        $custom_titles = $inputs['custom_titles'];
        $send_final_report = isset($inputs['send_final_report']) ? 1 : 0;
        $final_report_emails = trim($inputs['final_report_emails']);
        $final_report_emails_uw = trim($inputs['final_report_emails_uw']);
        $tilaauth = (int)$inputs['tila_auth'];
        $tilaemails = $inputs['tila_emails'];

        // Sales
        $salesid = (int)$inputs['salesid'];
        $salesid2 = (int)$inputs['salesid2'];
        $salesid_com = (float)$inputs['salesid_com'];
        $salesid2_com = (float)$inputs['salesid2_com'];
        $salesid_alt_com = (float)$inputs['salesid_alt_com'];
        $salesid2_alt_com = (float)$inputs['salesid2_alt_com'];
        $manager = (int)$inputs['manager'];
        $manager_com = (float)$inputs['manager_com'];
        $manager_alt_com = (float)$inputs['manager_alt_com'];

        // Logo and text
        $signupLogo = $inputs['signup_logo'];
        $signupNote = $inputs['signup_note'];
        $defaultWatchlist = $inputs['default_watch_list'];
        $eoInsuranceEach = $inputs['min_eoins_require_each'];
        $eoInsuranceAggregate = $inputs['min_eoins_require_agg'];

        try {
            // Add in the record
            DB::beginTransaction();
            $row = $this->lenders->create([
                'lender' => $lender,
                'lender_dropdown_title' => $lenderDropdownTitle,
                'lender_address1' => $lender_address1,
                'lender_address2' => $lender_address2,
                'lender_city' => $lender_city,
                'lender_state' => $lender_state,
                'lender_zip' => $lender_zip,
                'send_final_report' => $send_final_report,
                'final_report_emails' => $final_report_emails,
                'final_report_emails_uw' => $final_report_emails_uw,
                'comments' => $comments,
                'finalborroweremail' => $finalEmail,
                'finalborrowerpostal' => $finalMail,
                'enable_avm' => $enableAVM,
                'avm_require_payment' => $avmRequirePayment,
                'avm_fee' => $avmFee,
                'enable_docuvault' => $enableDocuVault,
                'docuvault_require_payment' => $docuVaultRequirePayment,
                'docuvault_fee' => $docuVaultFee,
                'mail_appr_addfee' => $docuVaultMailFee,
                'admin_notes' => $admin_notes,
                'enable_auto_select' => $autoselect,
                'custom_titles' => $custom_titles,
                'tila_auth' => $tilaauth,
                'tila_emails' => $tilaemails,
                'salesid' => $salesid,
                'salesid2' => $salesid2,
                'salesid_com' => $salesid_com,
                'salesid2_com' => $salesid2_com,
                'salesid_alt_com' => $salesid_alt_com,
                'salesid2_alt_com' => $salesid2_alt_com,
                'manager' => $manager,
                'manager_com' => $manager_com,
                'manager_alt_com' => $manager_alt_com,
                'signup_logo' => $signupLogo,
                'signup_note' => $signupNote,
                'default_watch_list' => $defaultWatchlist,
                'is_proposed_loan_amount' => $isProposedLoan,
                'min_eoins_require_each' => $eoInsuranceEach,
                'min_eoins_require_agg' => $eoInsuranceAggregate
            ]);

            // Add relations
            if ($clients && count($clients)) {
                foreach ($clients as $client) {
                    $this->clients->create([
                        'lenderid' => $row->id,
                        'groupid' => $client,
                    ]);
                }
            }

            // Add relations
            if ($states && count($states)) {
                foreach ($states as $state) {
                    UserGroupLenderStateRel::create([
                            'lenderid' => $row->id,
                            'state' => $state,
                    ]);
                }
            }

            // Update user group lenders
            $this->updateUserGroupLendersColumn();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
    * Update Lender
    * @return void
    */
    public function update($id, $inputs)
    {
        // Init
        $lender = trim($inputs['lender']);
        $lenderDropdownTitle = trim($inputs['lender_dropdown_title']);
        $lender_address1 = $inputs['lender_address1'];
        $lender_address2 = $inputs['lender_address2'];
        $lender_city = $inputs['lender_city'];
        $lender_state = $inputs['lender_state'];
        $lender_zip = $inputs['lender_zip'];
        $clients = $inputs['clients'];
        $states = isset($inputs['states']) ? $inputs['states'] : 0;
        $autoselect = (int)$inputs['enable_auto_select'];
        $comments = $inputs['comments'];
        $finalEmail = $inputs['finalborroweremail'];
        $finalMail = $inputs['finalborrowerpostal'];

        $enableAVM = $inputs['enable_avm'];
        $avmRequirePayment = $inputs['avm_require_payment'];
        $avmFee = $inputs['avm_fee'];

        $enableDocuVault = $inputs['enable_docuvault'];
        $docuVaultRequirePayment = $inputs['docuvault_require_payment'];
        $docuVaultFee = $inputs['docuvault_fee'];
        $docuVaultMailFee = $inputs['mail_appr_addfee'];

        $admin_notes = $inputs['admin_notes'];
        $custom_titles = $inputs['custom_titles'];
        $send_final_report = isset($inputs['send_final_report']) ? (int)$inputs['send_final_report'] : 0;
        $final_report_emails = trim($inputs['final_report_emails']);
        $final_report_emails_uw = trim($inputs['final_report_emails_uw']);
        $tilaauth = (int)$inputs['tila_auth'];
        $tilaemails = $inputs['tila_emails'];

        $defaultWatchlist = $inputs['default_watch_list'];

        $isProposedLoan = $inputs['is_proposed_loan_amount'];

        // Sales
        $salesid = (int)$inputs['salesid'];
        $salesid2 = (int)$inputs['salesid2'];
        $salesid_com = (float)$inputs['salesid_com'];
        $salesid2_com = (float)$inputs['salesid2_com'];
        $salesid_alt_com = (float)$inputs['salesid_alt_com'];
        $salesid2_alt_com = (float)$inputs['salesid2_alt_com'];
        $manager = (int)$inputs['manager'];
        $manager_com = (float)$inputs['manager_com'];
        $manager_alt_com = (float)$inputs['manager_alt_com'];

        // Logo and text
        $signupLogo = $inputs['signup_logo'];
        $signupNote = $inputs['signup_note'];
        $eoInsuranceEach = $inputs['min_eoins_require_each'];
        $eoInsuranceAggregate = $inputs['min_eoins_require_agg'];

        try {
            DB::beginTransaction();

            $this->lenders->where('id', $id)->update([
                'lender' => $lender,
                'lender_dropdown_title' => $lenderDropdownTitle,
                'lender_address1' => $lender_address1,
                'lender_address2' => $lender_address2,
                'lender_city' => $lender_city,
                'lender_state' => $lender_state,
                'lender_zip' => $lender_zip,
                'send_final_report' => $send_final_report,
                'final_report_emails' => $final_report_emails,
                'final_report_emails_uw' => $final_report_emails_uw,
                'comments' => $comments,
                'finalborroweremail' => $finalEmail,
                'finalborrowerpostal' => $finalMail,
                'enable_avm' => $enableAVM,
                'avm_require_payment' => $avmRequirePayment,
                'avm_fee' => $avmFee,
                'enable_docuvault' => $enableDocuVault,
                'docuvault_require_payment' => $docuVaultRequirePayment,
                'docuvault_fee' => $docuVaultFee,
                'mail_appr_addfee' => $docuVaultMailFee,
                'admin_notes' => $admin_notes,
                'enable_auto_select' => $autoselect,
                'custom_titles' => $custom_titles,
                'tila_auth' => $tilaauth,
                'tila_emails' => $tilaemails,
                'salesid' => $salesid,
                'salesid2' => $salesid2,
                'salesid_com' => $salesid_com,
                'salesid2_com' => $salesid2_com,
                'salesid_alt_com' => $salesid_alt_com,
                'salesid2_alt_com' => $salesid2_alt_com,
                'manager' => $manager,
                'manager_com' => $manager_com,
                'manager_alt_com' => $manager_alt_com,
                'signup_logo' => $signupLogo,
                'signup_note' => $signupNote,
                'default_watch_list' => $defaultWatchlist,
                'is_proposed_loan_amount' => $isProposedLoan,
                'min_eoins_require_each' => $eoInsuranceEach,
                'min_eoins_require_agg' => $eoInsuranceAggregate
            ]);

            // Delete all relations
            $this->clients->where('lenderid', $id)->delete();
            UserGroupLenderStateRel::where('lenderid', $id)->delete();

            // Add relations
            if ($clients && count($clients)) {
                foreach ($clients as $client) {
                    $this->clients->create([
                        'lenderid' => $id,
                        'groupid' => $client,
                    ]);
                }
            }

            // Add relations
            if ($states && count($states)) {
                foreach ($states as $state) {
                    UserGroupLenderStateRel::create([
                            'lenderid' => $id,
                            'state' => $state,
                    ]);
                }
            }

            // Update user group lenders
            $this->updateUserGroupLendersColumn();

            DB::commit();
            return true;
        } catch(\Exeption $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
    * Delete
    * @return void
    */
    public function delete($id)
    {
        return $this->lenders->where('id', $id)->delete();
    }

    /**
    * Update user group lenders
    * @return bool
    */
    public function updateUserGroupLendersColumn()
    {
        $groups = Client::select('id', 'lenders_used')->get();
        $items = [];

        foreach($groups as $group) {
            // Get lenders for this group
            $lenders = [];
            if($group->lenders_used) {
                $current = explode(',', $group->lenders_used);
                foreach($current as $c) {
                    $lenders[$c] = $c;
                }
            }

            $rows = $this->clients->select('lenderid')->where('groupid', $group->id)->get();
            if($rows && count($rows)) {
                foreach($rows as $r) {
                    $lenders[$r->lenderid] = $r->lenderid;
                }
            }

            $items[$group->id] = $lenders;
        }

        // Update groups
        foreach($items as $groupId => $values) {
            // Update
            if($values && count($values)) {
                Client::where('id', $groupId)->update(['lenders_used' => implode(',', $values)]);
            }
        }

        return true;
    }

    /**
    * import Excluded Users
    * @return array
    */
    public function importExcludedUsers($inputs)
    {
        // Init
        $lender = $inputs['lender'];
        $file = $inputs['lender_file'];


        // Check if the file has content
        $content = file_get_contents($file);
        if (!$content) {
            return ['error' => 'Sorry, The file uploaded is empty.'];
        }

        // Parse file
        $data = [];
        $content = str_replace("\r", "\n", $content);
        $lines = explode("\n", $content);
        $count = 0;
        $heads = [];
        $rows = [];
        $sep = ",";

        if(empty($lines[count($lines)-1])) {
            unset($lines[count($lines)-1]);
        }

        // Headers
        $headers = ['FIRSTNAME', 'LASTNAME', 'STATE', 'ZIP', 'LICENSE_STATE', 'LICENSE_NUMBER', 'EMAIL'];
        $headersCount = count($headers);

        foreach ($lines as $line) {
            if ($count == 0) {
                $heads = explode($sep, $line);
            } else {
                $rows[] = explode($sep, $line);
            }
            $count++;
        }

        // Loop over rows and add
        if ($rows) {
            foreach ($rows as $row) {
                // Make sure we have first name and last name
                if (!$this->cleanLenderValue($row[0]) && !$this->cleanLenderValue($row[1])) {
                    continue;
                }

                // make sure we have either state and zip or license number
                if (!$this->cleanLenderValue($row[2]) && !$this->cleanLenderValue($row[3]) && !$this->cleanLenderValue($row[5])) {
                    continue;
                }
                $values[] = [
                    'firstname' => $this->cleanLenderValue($row[0]),
                    'lastname' => $this->cleanLenderValue($row[1]),
                    'email' => $this->cleanLenderValue($row[6]),
                    'state' => $this->cleanLenderValue($row[2]),
                    'zip' => $this->cleanLenderValue($row[3]),
                    'license_state' => $this->cleanLenderValue($row[4]),
                    'license_number' => $this->cleanLenderValue($row[5]),
                ];
            }
        }

        if (count($heads) != $headersCount) {
            return ['error' => 'Sorry, There the number of columns does not match the number of expected columns.'];
        }

        // Loop over the headers and grab the rows data
        $items = [];
        if (!count($values)) {
            return ['error' => 'Sorry, There were no records to process.'];
        }

        $row = $this->getLenderById($lender);

        if (is_null($row)) {
            return ['error' => 'Sorry, We could not find that record.'];
        }

        $updated = 0;
        $imported = 0;
        $skipped = 0;

        $insertedRows = [];
        $skippedRows = [];

        if ($values && count($values)) {
            foreach ($values as $value) {
                // See if we have that user already  ExcludedLicenses
                if ($value['email']) {
                    $row = ExcludedLicenses::where('lender_id', $lender)->where(\DB::raw('LOWER(email)'), strtolower($value['email']))->first();
                    if (!is_null($row)) {
                        $skipped++;
                        $skippedRows[] = $value;
                        continue;
                    }
                }
                // Firstname, lastname, state and zip
                if ($value['state'] && $value['zip'] && !$value['license_number']) {
                    $row = ExcludedLicenses::where('lender_id', $lender)
                                            ->where(\DB::raw('LOWER(firstname)'), strtolower($value['firstname']))
                                            ->where(\DB::raw('LOWER(lastname)'), strtolower($value['lastname']))
                                            ->where(\DB::raw('LOWER(state)'), strtolower($value['state']))
                                            ->where('zip', $value['zip'])->first();
                    if (!is_null($row)) {
                        $skipped++;
                        $skippedRows[] = $value;
                        continue;
                    }
                } elseif ($value['state'] && $value['zip'] && $value['license_number']) {
                    $row = ExcludedLicenses::where('lender_id', $lender)
                                            ->where(\DB::raw('LOWER(firstname)'), strtolower($value['firstname']))
                                            ->where(\DB::raw('LOWER(lastname)'), strtolower($value['lastname']))
                                            ->where(\DB::raw('LOWER(state)'), strtolower($value['state']))
                                            ->where(\DB::raw('LOWER(license_number)'), strtolower($value['license_number']))
                                            ->where('zip', $value['zip'])->first();
                    if (!is_null($row)) {
                        $skipped++;
                        $skippedRows[] = $value;
                        continue;
                    }
                }
                // License state and number
                if ($value['license_state'] && $value['license_number']) {
                    $row = ExcludedLicenses::where('lender_id', $lender)
                                            ->where(\DB::raw('LOWER(license_state)'), strtolower($value['license_state']))
                                            ->where(\DB::raw('LOWER(license_number)'), strtolower($value['license_number']))->first();
                    if (!is_null($row)) {
                        $skipped++;
                        $skippedRows[] = $value;
                        continue;
                    }
                }
                // Insert new row
                try {
                    ExcludedLicenses::create([
                        'lender_id' => $lender,
                        'firstname' => strtolower($value['firstname']),
                        'lastname' => strtolower($value['lastname']),
                        'email' => strtolower($value['email']),
                        'state' => $value['state'],
                        'zip' => $value['zip'],
                        'license_state' => $value['license_state'],
                        'license_number' => $value['license_number'],
                    ]);
                    $imported++;
                    $insertedRows[] = $value;
                } catch (\Exception $e) {
                    return ['error' => $e->getMessage()];
                }
            }
        }
        // Set flash
        return ['success' => sprintf("Import Complete. Imported %s, Skipped %s", number_format($imported), number_format($skipped))];
    }

    /**
    * clean Lender Value
    * @return string
    */
    public function cleanLenderValue($t)
    {
        $t = trim($t);
        $t = str_replace('$', '', $t);
        $t = str_replace(',', '', $t);
        $t = str_replace('"', '', $t);
        return $t;
    }
}
