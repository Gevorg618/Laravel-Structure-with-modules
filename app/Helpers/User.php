<?php

use App\Models\Users\User;

function admin()
{
    return \Auth::guard('admin')->user();
}

function user()
{
    return \Auth::guard()->user();
}

function publicUser()
{
    return collect(\Auth::guard()
                  ->user())
                  ->only([
                    'id', 'email', 'fullname', 'initials', 'register_date',
                    'user_type', 'groupid',
                    'isGroupManager', 'isGroupSupervisor',
                    'isWholesaleLenderManager', 'isDocuvaultEnabled',
                    'isAVMEnabled'])
                ->all();
}

function admins()
{
    return App\Models\Users\User::admins()->get();
}

function adminTeams()
{
    $teams = [];
    $rows = App\Models\Management\AdminTeamsManager\AdminTeam::all();

    foreach ($rows as $row) {
        $teams[$row->id] = $row->team_title;
    }

    return $teams;
}

function userInfo($id, $full = false)
{
    if ($full) {
        return App\Models\Users\User::where('id', $id)
            ->leftJoin('user_data', 'user_data.user_id', '=', 'user.id')
            ->first();
    }

    return App\Models\Users\User::find($id);
}

function userTypeIds()
{
    return App\Models\Management\UserType::all()->implode('id', ',');
}

function userTypes()
{
    return App\Models\Management\UserType::all();
}

function userAllTypes()
{
    $user_types = [];
    foreach (App\Models\Management\UserType::all() as $value) {
        $user_types[$value->id] = $value->descrip;
    }
    return $user_types;
}

function multiselect($collection, $fields, $key = 'id')
{
    return \App\Helpers\Widget::multiselect($collection, $fields, $key);
}

function countActiveUsersByType($type, $time)
{
    return App\Models\Session::countActiveUsersByType($type, Carbon\Carbon::now()->subMinute($time)->timestamp);
}

function getActiveUsersByType($type, $time)
{
    return App\Models\Session::getActiveUsersByType($type, Carbon\Carbon::now()->subMinute($time)->timestamp);
}

function countActiveUsersByTypeForApp($type, $time)
{
    return App\Models\Session::countActiveUsersByTypeForApp($type, Carbon\Carbon::now()->subMinute($time)->timestamp);
}

function getActiveUsersByTypeForApp($type, $time)
{
    return App\Models\Session::getActiveUsersByTypeForApp($type, Carbon\Carbon::now()->subMinute($time)->timestamp);
}

function getUserId()
{
    if (admin()) {
        return admin()->id;
    } elseif (user()) {
        return user()->id;
    }
    return 0;
}

function isAdmin()
{
    return getUserPrivilege() == 'S';
}


function isSalesPerson()
{
    return in_array(getUserPrivilege(), array('S', 'O'));
}

function isVendor()
{
    return isAppr() || isAgent();
}

function userIsSalesPerson(User $user)
{
    return in_array($user->admin_priv, ['S', 'O']);
}



function isClient()
{
    return user()->isClient();
}

function getUserOrSuper()
{
    $userId = getUserId();
    return !php_sapi_name()==="cli" && isset($_SESSION) && $userId ? $userId : config('users.superUserId');
    ;
}


//function userIsSalesPerson(\App\Models\User $user) {
//    return in_array($user->admin_priv, ['S', 'O']) ? true : false;
//}

function isAppr() {
    return in_array(getUser()->user_type, [4]) ? true : false;
}

function isAgent() {
    return in_array(getUser()->user_type, [14]) ? true : false;
}

function getUser() {
    if (admin()) {
        return admin();
    }
    return user();
}

function getUserPrivilege()
{
    if (admin()) {
        return admin()->admin_priv;
    } elseif (user()) {
        return user()->admin_priv;
    }
}


function getUserEmailById($id)
{
    $user = userInfo($id, true);
    return $user ? $user->email : '';
}



function canLoginAsUser($user) {
    $canLogin = true;
    if($user->user_type == 1) {
        $canLogin = false;
    }

    if(in_array(getUserId(), array(124566)) && $user->id != getUserId()) {
        $canLogin = true;
    }

    return $canLogin;
}

function isAdminUser() {
    $user = null;
    if (admin()) {
        $user = admin();
    } else {
        $user = user();
    }
    return (in_array(getUserPrivilege(), array('S', 'T', 'R', 'O')) && $user->user_type == 1) ? true : false;
}

function getUserFullNameById($id, $userType=false, $separator='<br />') {
	$user = userInfo($id, $full = true);
	$type = '';
	if($user) {
	    return $user->firstname . ' ' . $user->lastname;
    } else {
        return $id;
    }
}

function getAdminReasonTitleByKey($key)
{
    $rows = getAdministrativeReasonCats();
    return isset($rows[$key]) ? $rows[$key]['name'] : 'N/A';
}

function getAdministrativeReasonCats()
{
    return [
        'stoppayment' => [
            'name' => 'Stop Payment',
            'id' => 'stoppayment',
            'invoice_visible' => true,
        ],
        'nsf' => [
            'name' => 'NSF',
            'id' => 'nsf',
            'invoice_visible' => true,
        ],
        'paidback_nsf' => [
            'name' => 'Paid Back NSF',
            'id' => 'paidback_nsf',
            'invoice_visible' => true,
        ],
        'chargeback' => [
            'name' => 'Chargeback',
            'id' => 'chargeback',
            'invoice_visible' => true,
        ],
        'makeitright' => [
            'name' => 'Make It Right',
            'id' => 'makeitright',
            'invoice_visible' => true,
        ],
        'writeoff' => [
            'name' => 'Write Off',
            'id' => 'writeoff',
            'invoice_visible' => false,
        ],
        'settlement' => [
            'name' => 'Settlement',
            'id' => 'settlement',
            'invoice_visible' => false,
        ],
        'transfer' => [
            'name' => 'Transfer',
            'id' => 'transfer',
            'invoice_visible' => false,
        ],
        'manualentry' => [
            'name' => 'Manual Entry',
            'id' => 'manualentry',
            'invoice_visible' => false,
        ],
        'reversal' => [
            'name' => 'Reversal',
            'id' => 'reversal',
            'invoice_visible' => false,
        ],
        'puerto_rico_taxes' => [
            'name' => 'Puerto Rico Taxes',
            'id' => 'puerto_rico_taxes',
            'invoice_visible' => false,
        ],
        'compliance' => [
            'name' => 'Compliance',
            'id' => 'compliance',
            'invoice_visible' => false,
        ],
        'refund' => [
            'name' => 'Refund',
            'id' => 'refund',
            'invoice_visible' => false,
        ],
        'creditmemo' => [
            'name' => 'Credit Memo',
            'id' => 'creditmemo',
            'invoice_visible' => false,
        ],
    ];
}

function getAdminAmountTypeSymbol($key)
{
    switch ($key) {
        case 'subtract':
            return '-';

        case 'add':
            return '+';
    }

    return '';
}

function getUserReportManagerHeaders()
{
    return [
        'id' => 'User ID',
        'email' => 'Email Address',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        //'exclude' => 'Exclude',
        'user_type' => 'User Type',
        'joined' => 'Joined Date',
        'active' => 'Active',
        'groupid' => 'User Group',
        'notes' => 'Notes',
        'referral' => 'Referral',

        'is_priority' => 'Is Priority',
        'appr_priority_invite_accepted_date' => 'Date Priority Invite Accepted',
        'appraisal_software' => 'Appraisal Software',

        // Other
        'title' => 'Title',
        'phone' => 'Phone',
        'mobile' => 'Mobile',
        'fax' => 'Fax',
        'address' => 'Address',
        'city' => 'City',
        'state' => 'State',
        'county' => 'County',
        'zip' => 'Zip',
        'comp_address' => 'Company Address',
        'comp_city' => 'Company City',
        'comp_state' => 'Company State',
        'comp_zip' => 'Company Zip',
        'company' => 'Company Name',
        'date_company_joined' => 'Date Company Joined',
        'client_pricing_version' => 'Client Pricing Version',
        'last_order_placed_date' => 'Last Ordered Placed Date',

        // Appraiser/agent
        'payable_address' => 'Payable Address',
        'payable_city' => 'Payable City',
        'payable_state' => 'Payable State',
        'payable_zip' => 'Payable Zip',
        'payable_company' => 'Payable Company',

        'ein' => 'EIN/SSN',
        'ins_company' => 'Insurance Company',
        'ins_expire' => 'Insurance Expiration',

        'eando_each' => 'E&O Amount (Each)',
        'eando_aggregate' => 'E&O Amount (Aggregate)',

        // Appraiser
        'fha' => 'Is FHA',
        'fha_fee' => 'FHA Fee',
        'software_charge' => 'Software Charge',
        'is_away' => 'Is Away',
        'away_start_date' => 'Away Start Date',
        'away_end_date' => 'Away End Date',
        'is_state_compliance_marked' => 'State Compliance Marked',
        'state_compliance_date' => 'State Compliance Date',
        'appr_state_compliance_approved' => 'State Compliance Approved',

        'total_orders_placed' => 'Orders Placed',
        'total_orders_accepted' => 'Orders Accepted',
        'total_orders_completed' => 'Orders Completed',

        'date_orders_placed' => 'Date Orders Placed',
        'date_orders_accepted' => 'Date Orders Accepted',
        'date_orders_completed' => 'Date Orders Completed',

        'total_avg_turn_time' => 'Placed - Delivered TT',
        'accepted_avg_turn_time' => 'Accepted - Delivered TT',

        '90_total_avg_turn_time' => 'Placed - Delivered TT (90 Days)',
        '90_accepted_avg_turn_time' => 'Accepted - Delivered TT (90 Days)',

        'qc_avg_turn_time' => 'QC TT',
        'uw_avg_turn_time' => 'UW TT',

        '90_qc_avg_turn_time' => 'QC TT (90 Days)',
        '90_uw_avg_turn_time' => 'UW TT (90 Days)',

        'last_order_accepted_date' => 'Last Order Accepted',
        'last_order_completed_date' => 'Last Order Completed',

        'fha_license' => 'FHA License Number',
        'fha_license_expiration' => 'FHA License Expiration',
        'asc_license' => 'ASC License Number',
        'asc_license_expiration' => 'ASC License Expiration',
        'asc_license_type' => 'ASC License Type',
        'state_license' => 'State License Number',
        'state_license_type' => 'State License Type',
        'state_license_state' => 'State License State',
        'state_license_expiration' => 'State License Expiration',

        // Diversity
        'diversity_status' => 'Diversity Status',
        'diversity_type' => 'Diversity Type',
        'diversity_agency_type' => 'Diversity Agency Type',
        'diversity_agency_type_other' => 'Diversity Agency Type Other',
        'diversity_agency_certify_agency' => 'Diversity Certifying Agency',
        'diversity_agency_certificate_number' => 'Diversity Certificate Number',
        'diversity_agency_effective_date' => 'Diversity Effective Date',
        'diversity_agency_expiration_date' => 'Diversity Agency Expiration Date',
    ];
}
