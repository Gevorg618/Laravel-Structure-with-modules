<?php

namespace Modules\Admin\Http\Controllers;

use App\Jobs\SendUserEmail;
use App\Models\Documents\UserDoc;
use App\Models\Documents\UserDocument;
use App\Models\Management\AdminGroup\AdminPermissionCategory;
use App\Models\User;
use App\Models\UserNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Http\Requests\UserAddNoteRequest;
use Modules\Admin\Http\Requests\UserUpdateRequest;
use Modules\Admin\Services\TigerService;
use Modules\Admin\Services\UserPermissionService;
use Modules\Admin\Services\UsersService;

class UsersController extends Controller
{
    protected $service;
    protected $tigerService;
    protected $userPermissionService;

    /**
     * UsersController constructor.
     * @param $service
     */
    public function __construct(
        UsersService $service,
        TigerService $tigerService,
        UserPermissionService $userPermissionService
    )
    {
        $this->service = $service;
        $this->tigerService = $tigerService;
        $this->userPermissionService = $userPermissionService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $rows = $this->service->getUsers($request->all());
        return view('admin::users.index', [
            'userTypes' => $this->service->getUserTypes(),
            'rows' => $rows,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(User $user, AdminPermissionCategory $adminPermissionCategory)
    {
        if (!$user) {
            return redirect(route('admin.users.index'))
                ->with('error', 'User not found');
        }

        $backgroundDocument = $this->service->getUserBackgroundCheckDocument($user->id);
        $eandoDocument = $this->service->getUserInsDocument($user->id);
        $userLicenseDocument = $this->service->getUserLicenseDocument($user->id);
        $userW9Document = $this->service->getUserW9Document($user->id);

        return view('admin::users.show', [
            'user' => $user,
            'alerts' => $this->service->setAlerts($user),
            'completed' => $user->getApprOrdersCompleted(),
            'apprGroup' => $this->service->getAppraiserGroupByUserId($user->id),
            'adminPermissionCategory' => $adminPermissionCategory,
            'userTypes' => $this->service->getUserTypes(),
            'userGroups' => $this->service->getUserGroups(),
            'suffixList' => $this->service->getUserSuffixList(),
            'clientsList' => $this->service->getClients(),
            'lenderManager' => $this->service->getWholeSaleManagerLenderId($user),
            'backgroundDocument' => $backgroundDocument,
            'backgroundLink' => $this->service->getUserDocumentLink($backgroundDocument),
            'eandoDocument' => $eandoDocument,
            'eandoLink' => $this->service->getUserDocumentLink($eandoDocument),
            'userDocumentTypes' => $this->service->getUserDocumentTypes(),
            'additionalDocuments' => $this->service->getAdditionalDocuments($user->id),
            'userOldHUDLicenses' => $this->service->getUserOldHUDLicenses($user),
            'cachedFhaLicenses' => $this->service->getAppraiserCachedFHALicenses($user->od),
            'userOldASCLicenses' => $this->service->getUserOldASCLicenses($user),
            'service' => $this->service,
            'appraiserCachedASCLicenses' => $this->service->getAppraiserCachedASCLicenses($user->id),
            'appraiserStateLicenses' => $this->service->getAppraiserStateLicenses($user->id),
            'userLicenseDocument' => $userLicenseDocument,
            'userLicenseDocumentLink' => $this->service->getUserDocumentLink($userLicenseDocument),
            'userTaxClasses' => $this->service->getUserTaxClasses(),
            'userW9Document' => $userW9Document,
            'userW9DocumentLink' => $this->service->getUserDocumentLink($userW9Document),
            'documents' => $this->service->getUserDocuments($user->id),
            'yesNo' => ['0' => 'No', '1' => 'Yes'],
            'yesNoWord' => ['N' => 'No', 'Y' => 'Yes'],
            'stateComplianceTakenStates' => $this->service->getStateComplianceTakenStates(),
            'userExcludeTitle' => $this->service->getUserExcludeTitle($user->exclude),
            'appraisalSoftwareList' => $this->service->getAppraisalSoftwareList(),
            'phoneTypes' => $this->service->getPhoneTypes(),
            'phoneProviders' => $this->service->getPhoneProviders(),
            'selectedLanguages' => $this->service->getUserSelectedLanguages($user->id),
            'languages' => $this->service->getLanguages(),
            'selectedApprTypes' => $this->service->getUserSelectedApprTypes($user->id),
            'apprTypes' => $this->service->getApprTypeList(),
            'selectedLoanTypes' => $this->service->getUserSelectedLoanTypes($user->id),
            'loanTypes' => $this->service->getLoanTypeList(),
            'selectedLoanPurposes' => $this->service->getUserSelectedLoanPurposes($user->id),
            'loanPurposes' => $this->service->getLoanPurposeList(),
            'prefs' => $this->service->getUserReferrences($user->id),
            'splitValues' => $this->service->getAppraiserSplitValuesById($user->id),
            'payments' => $this->service->getPayments($user),
            'invites' => $this->service->getOrderAppraiserAssignmentInvitesByApprIdAdmin($user->id),
            'inviteCounts' => $this->service->getAppraiserInviteCounts($user->id),
            'communicationMethods' => $this->service->communicationTypes(),
            'selectedCommunication' => $this->service->getSelectedCommunicationMethods($user->id),
            'businessDays' => $this->service->daysOfOperation(),
            'businessHours' => $this->service->hoursOfOperation(),
            'selectedHours' => $this->service->getSelectedBusinessHours($user->id),
            'ordersPerPage' => 20,
            'totalOrders' => $this->service->getOrdersAcceptedByUserId($user->id),
            'orders' => $this->service->getOrdersAcceptedByUserIdWithLimit($user->id, 20),
            'agentOrders' => $this->service->getUserAgentOrders($user->id),
            'agentSubOrders' => $this->service->getUserAgentSubOrders($user->id),
            'notes' => $this->service->getUserNotes($user->id),
            'ordersCompleted' => $this->service->getApprOrdersCompletedByUserIdWithDeliveredDate($user->id),
            'turnOut' => $this->service->getAppraiserOrdersTurnOut($user->id),
            'qcStats' => $this->service->getAppraiserOrdersWithQCCorrections($user->id),
            'uwStats' => $this->service->getAppraiserOrdersWithUWConditions($user->id),
            'excluded' => $user->excludedGroups,
            'lenders' => $user->excludedProfiles,
            'appraiserAverage' => $this->service->getAppraiserAverageTurnTime($user->id),
            'daysRangeAverage' => $this->service->getAppraiserAverageTurnTimeDaysRange($user->id),
            'stateCompliance' => $this->service->getStateComplianceRecordByState($user->comp_state),
            'preferredGroups' => $this->service->getAppraiserPreferredGroups($user->id),
            'emailTemplates' => $this->service->getEmailTemplatesByCategory($user->id),
            'profile' => $this->service->getUserLastFirstDataProfile($user->id),
            'activities' => $this->service->getActivityRecords($user->id),
            'categories' => $this->userPermissionService->categories(),
            'userPermissionService' => $this->userPermissionService,
            'amcList' => $this->service->getAmcList(),
            'apiAccountsList' => $this->service->getAPIAccounts(),
            'adminTypes' => $this->service->getAdminUserTypes(),
            'adminGroupsList' => $this->service->getAdminGroupsDropdown(),
            'formattedAdminGroupName' => $this->service->getAdminGroupFormattedTitle($user->admin_group),
            'perms' => $this->service->getAdminGroupPermissions(),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UserUpdateRequest $request, User $user, AdminPermissionCategory $adminPermissionCategory)
    {
        $error = false;
        $errors = [];

        // Init
        $firstname = trim($request->post('firstname'));
        $lastname = trim($request->post('lastname'));
        $title = trim($request->post('title'));
        $middlename = trim($request->post('middlename'));
        $suffix = $request->post('suffix');
        $phone = StringHelper::getNumbersOnly($request->post('phone'));
        $mobile = StringHelper::getNumbersOnly($request->post('mobile'));
        $fax = StringHelper::getNumbersOnly($request->post('fax'));
        $phoneext = StringHelper::getNumbersOnly($request->post('phoneext'));
        $userType = intval($request->post('user_type'));
        $userGroupId = intval($request->post('user_group_id'));

        $twitter = $request->post('twitter');
        $linkedin = $request->post('linkedin');

        // Password
        $newPassword = trim($request->post('new_password'));

        // Company
        $company = trim($request->post('company'));
        $companyAddress = trim($request->post('comp_address'));
        $companyAddress1 = trim($request->post('comp_address1'));
        $companyCity = trim($request->post('comp_city'));
        $companyState = trim($request->post('comp_state'));
        $companyZip = trim($request->post('comp_zip'));

        // Payable
        $payable = trim($request->post('payable_company', $user->payable_company));
        $payableAddress = trim($request->post('payable_address', $user->payable_address));
        $payableAddress1 = trim($request->post('payable_address1', $user->payable_address1));
        $payableCity = trim($request->post('payable_city', $user->payable_city));
        $payableState = trim($request->post('payable_state', $user->payable_state));
        $payableZip = trim($request->post('payable_zip', $user->payable_zip));

        $enableTextInvites = intval($request->post('enable_text_invites'));

        // Integrations
        $amcAPIAccountId = $request->post('amc_api_account', $user->amc_api_account);
        $amcAPIAccountAPIId = $request->post('amc_api_account_api_id', $user->amc_api_account_api_id);

        // Admin specific
        $adminPriv = false;
        $adminGroup = false;
        $showInAssign = false;
        $supervising = false;


        if ($request->post('supervising_users')) {
            $supervising = $request->post('supervising_users');
        }

        if ($request->post('admin_priv')) {
            $adminPriv = $request->post('admin_priv');
        }

        if ($request->post('admin_group')) {
            $adminGroup = $request->post('admin_group');
        }

        if ($request->post('show_in_assign')) {
            $showInAssign = $request->post('show_in_assign');
        }


        $minimumMargin = floatval($request->post('margin_minimum'));
        $alminimumMargin = floatval($request->post('al_margin_minimum'));

        $comission = floatval($request->post('comission'));
        $alcomission = floatval($request->post('al_comission'));

        // Appraiser/agent
        $constructionExpert = intval($request->post('new_construction_expert'));
        $acceptCod = intval($request->post('accept_cod'));
        $bypassLicense = intval($request->post('is_allowed_license_bypass'));
        $isPriority = intval($request->post('is_priority_appr'));
        $softwareFee = intval($request->post('software_charge'));
        $paymentEmailNotification = intval($request->post('payment_email_notification'));
        $paymentSMSNotification = intval($request->post('payment_sms_notification'));

        $isAutoSelectPriority = intval($request->post('is_auto_select_priority'));
        $isInHouse = intval($request->post('is_in_house'));
        $isZeroFee = intval($request->post('is_zero_fee'));

        $stateCompliance = $user->appr_state_compliance_approved;
        if (in_array($companyState, $this->service->getStateComplianceTakenStates()->toArray()) && checkPermission($adminPermissionCategory, 'user_allow_change_state_compliance')) {
            $stateCompliance = intval($request->post('appr_state_compliance_approved'));
        }

        $autoSelectEnabled = intval($request->post('autoselect_enabled'));

        // Special Instructions
        $userNotes = $request->post('user_notes');
        $emailSignature = $request->post('email_signature');

        $exclude = $request->post('exclude', 'N');
        $capacity = intval($request->post('capacity'));

        $phoneType = $request->post('phone_type');
        $phoneProvider = $request->post('phone_provider');

        $ein = trim($request->post('ein'));
        $taxClass = trim($request->post('tax_class'));

        $posLat = $user->pos_lat;
        $posLong = $user->pos_long;

        $setPostLat = $request->post('pos_lat');
        $setposLong = $request->post('pos_long');

        if ($setPostLat != '') {
            $posLat = $setPostLat;
        }

        if ($setposLong != '') {
            $posLong = $setposLong;
        }

        $addTaxEntry = false;

        // Make sure we are not deleting the ein
        if (!$ein && $user->ein) {
            $ein = $user->ein;
        }

        // Make sure we are not deleting the tax class
        if (!$taxClass && $user->tax_class) {
            $taxClass = $user->tax_class;
        }

        if ($ein && $ein != $user->ein) {
            $addTaxEntry = true;
        }

        if ($taxClass && $taxClass != $user->tax_class) {
            $addTaxEntry = true;
        }

        $isFHA = $request->post('fha');

        // License Zip
        $licenseZip = $request->post('state_license_zips');
        $stateLicenseInfo = $request->post('state_license_info');

        // Away
        $isAway = intval($request->post('is_away'));
        $isDigest = intval($request->post('daily_digest'));
        $awayStartDate = $request->post('away_start_date');
        $awayEndDate = $request->post('away_end_date');

        // Appraisal Software
        $apprSoftware = $request->post('appr_software');

        // Background Check
        $hasBackgroundCheck = intval($request->post('has_background_check'));
        $backroundCheckDate = $request->post('background_check_date');

        // Insurance
        $insuranceCompany = trim($request->post('ins_company'));
        $insuranceAmount = intval($request->post('ins_amt'));
        $insuranceAmountAgg = intval($request->post('ins_amt_agg'));
        $insuranceExpire = trim($request->post('ins_expire'));

        // License
        $licenseState = trim($request->post('license_state'));
        $licenseNumber = trim($request->post('license_num'));
        $licenseExpire = trim($request->post('license_exp_date'));

        // Client
        $group = intval($request->post('groupid'));
        $email = $request->post('email');

        // Password change
        $password = $user->password;
        if ($newPassword) {
            $password = \Hash::make($newPassword);
        }

        // Validate admin
        if ($user->user_type == 1) {
            // Check if we are not super and trying to promote ourself to super
            if (!isAdmin() && $adminPriv == 'S') {
                $errors[] = sprintf("Sorry, You are not allowed to promote users to the super user privilege.");
                $error = true;
            } elseif (!isAdmin() && $user->admin_priv == 'S' && $adminPriv != 'S') {
                $errors[] = sprintf("Sorry, You are not allowed to demote users from the super user privilege.");
                $error = true;
            }
        }

        // Validate appraiser away
        if ($user->user_type == 4) {

            if ($isAway) {
                // Make sure both fields were fields out
                if (!$awayStartDate) {
                    $errors[] = sprintf("Sorry, You must enter the appraiser away start date.");
                    $error = true;
                } elseif (!$awayEndDate) {
                    $errors[] = sprintf("Sorry, You must enter the appraiser away end date.");
                    $error = true;
                } // Make sure start date is before end date
                elseif (strtotime($awayStartDate) >= strtotime($awayEndDate)) {
                    $errors[] = sprintf("Sorry, You must select valid dates for the away status.");
                    $error = true;
                }
            } else {
                // Empty date fields
                $awayStartDate = '';
                $awayEndDate = '';
            }
        }

        if (!$error) {
            // Update splits if it's an appraiser
            if ($user->user_type == 4) {
                if ($request->post('languages')) {
                    $languages = $request->post('languages');

                    try {
                        // delete all user types
                        $this->service->deleteAllLanguagesByUser($user->id);

                        // Insert new
                        $languageList = [];
                        foreach ($languages as $languageId) {
                            $languageList[] = [
                                'user_id' => $user->id,
                                'language_id' => $languageId
                            ];
                        }
                        $this->service->insertLanguagesByArray($languageList);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }

                if ($request->post('user_appr_types')) {
                    $user_appr_types = $request->post('user_appr_types');

                    try {
                        // delete all user types
                        $this->service->deleteUserApprTypes($user->id);

                        // Insert new
                        $insertData = [];
                        foreach ($user_appr_types as $apprTypeId) {
                            $insertData[] = [
                                'user_id' => $user->id,
                                'appr_type_id' => $apprTypeId
                            ];
                        }
                        $this->service->insertApprTypesByArray($insertData);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }

                if ($request->post('user_loan_types')) {
                    $user_loan_types = $request->post('user_loan_types');

                    try {
                        // delete all user types
                        $this->service->deleteUserLoanTypes($user->id);

                        // Insert new
                        $insertData = [];
                        foreach ($user_loan_types as $loanTypeId) {
                            $insertData[] = [
                                'user_id' => $user->id,
                                'loan_type_id' => $loanTypeId
                            ];
                        }
                        $this->service->insertLoanTypesByArray($insertData);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }

                if ($request->post('user_loan_purpose')) {
                    $user_loan_purpose = $request->post('user_loan_purpose');

                    try {
                        // delete all user types
                        $this->service->deleteUserLoanPurposes($user->id);

                        // Insert new
                        $insertData = [];
                        foreach ($user_loan_purpose as $loanPurposeId) {
                            $insertData[] = [
                                'user_id' => $user->id,
                                'loan_purpose_id' => $loanPurposeId,

                            ];
                        }
                        $this->service->insertLoanPurposesByArray($insertData);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }

                if ($request->post('references')) {
                    $refs = $request->post('references');

                    try {
                        // delete all user types
                        $this->service->deleteApprReferences($user->id);

                        // Insert new
                        $insertData = [];
                        foreach ($refs as $refid => $values) {
                            if (!$values['firstname']) {
                                continue;
                            }
                            $insertData[] = [
                                'user_id' => $user->id,
                                'firstname' => $values['firstname'],
                                'lastname' => $values['lastname'],
                                'company' => $values['company'],
                                'phone' => $values['phone'],
                            ];
                        }
                        $this->service->insertApprReferencesByArray($insertData);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }

                if ($request->post('appr_types')) {
                    $types = $request->post('appr_types');

                    try {
                        // delete all user types
                        $this->service->deleteUserAppraiserSplit($user->id);

                        // Insert new
                        $insertData = [];
                        foreach ($types as $typeId => $val) {
                            $insertData[] = [
                                'userid' => $user->id,
                                'apprid' => $typeId,
                                'fha' => intval($val['fha']),
                                'conv' => intval($val['conv'])
                            ];
                        }
                        $this->service->insertApprSplitByArray($insertData);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }

                // License Zip codes
                if ($licenseZip && count($licenseZip)) {
                    try {
                        // Delete current user data for this license
                        $this->service->deleteApprEmailRules($user->id);

                        $insertData = [];
                        foreach ($licenseZip as $state => $zipCodes) {
                            foreach ($zipCodes as $zip) {
                                $insertData[] = [
                                    'user_id' => $user->id,
                                    'state' => $state,
                                    'zip' => $zip
                                ];
                            }
                        }
                        $this->service->insertEmailRulesByArray($insertData);
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                } else {
                    $this->service->deleteApprEmailRules($user->id);
                }

                // Update state license info
                if ($stateLicenseInfo && count($stateLicenseInfo)) {
                    foreach ($stateLicenseInfo as $licenseId => $licenseData) {
                        $licenseRow = $this->service->findCert($licenseId, $user->id);
                        if (!$licenseRow) {
                            continue;
                        }

                        $licenseState = $licenseRow->state;

                        if (!$licenseData['number'] || !$licenseData['expire']) {
                            $error = true;
                            $errors[] = "Please Enter license number and expiration for all state licenses.";
                        } else {
                            // Update license info
                            try {
                                $updateData = [
                                    'cert_num' => $licenseData['number'],
                                    'cert_expire' => $licenseData['expire']
                                ];
                                $licenseRow->update($updateData);
                            } catch (\Exception $e) {
                                $error = true;
                                $errors[] = $e->getMessage();
                            }
                        }
                    }
                }
            }

            if (checkPermission($adminPermissionCategory, 'can_change_user_group_supervisor')) {
                // Delete current user data for this license
                $user->groups()->detach();

                // Additional Groups
                if ($request->post('additional_groups')) {
                    try {
                        $user->groups()->attach($request->post('additional_groups'));
                    } catch (\Exception $e) {
                        return redirect(route('admin.users.show', [$user->id]))
                            ->with('error', $e->getMessage());
                    }
                }
            }

            try {
                // Update info
                $user->email = $email;
                $user->password = $password;
                $user->groupid = $group;
                $user->user_type = $userType;
                $user->user_group_id = $userGroupId;
                $user->twitter = $twitter;
                $user->linkedin = $linkedin;
                $user->last_updated = Carbon::now()->timestamp;
                $user->save();

                // If this is a client and we are changing the group
                // update the orders he placed with the new group
                if (($group != $user->groupid) && $user->user_type == User::USER_TYPE_CLIENT) {
                    $this->service->convertAppraisalOrderGroups($user, $user->groupid, $group);

                    // Remove the user from the previous group if he was a group manager in that group
                    $oldGroupData = $this->service->getGroupInfoById($user->groupid);
                    if ($oldGroupData) {
                        $managers = [];
                        if ($oldGroupData->mgrids) {
                            $oldGroupData->mgrids = ltrim($oldGroupData->mgrids, ",");
                            $oldGroupData->mgrids = rtrim($oldGroupData->mgrids, ",");
                            $managers = explode(',', $oldGroupData->mgrids);
                        }
                        // Remove user
                        if (($key = array_search($user->id, $managers)) !== false) {
                            unset($managers[$key]);
                            // Save
                            try {
                                $this->service->updateClientsById($oldGroupData->id, [
                                    'mgrids' => implode(',', $managers),
                                ]);
                            } catch (\Exception $e) {
                                echo json_encode(array('error' => $e->getMessage()));
                                exit;
                            }
                        }
                    }
                }

                // Admin update
                if ($user->user_type == 1) {
                    // Update admin team user type and admin team id
                    $user->admin_priv = $adminPriv ?? $user->admin_priv;
                    $user->admin_group = $adminGroup ?? $user->admin_group;
                    $user->show_in_assign = $showInAssign ?? $user->show_in_assign;

                    // Add supervising if we have some
                    if ($supervising !== false) {
                        $this->service->saveSuperVisingUser($user->id, $supervising);
                    }
                }

                // Global
                $update = [
                    'user_id' => $user->id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'middlename' => $middlename,
                    'suffix' => $suffix,
                    'title' => $title,
                    'phone' => $phone,
                    'mobile' => $mobile,
                    'fax' => $fax,
                    'phoneext' => $phoneext,
                    'company' => $company,
                    'comp_address' => $companyAddress,
                    'comp_address1' => $companyAddress1,
                    'comp_city' => $companyCity,
                    'comp_state' => $companyState,
                    'comp_zip' => $companyZip,
                    'user_notes' => $userNotes,
                    'email_signature' => $emailSignature,
                    'amc_api_account' => intval($amcAPIAccountId),
                    'amc_api_account_api_id' => intval($amcAPIAccountAPIId),
                ];

                // Update specific table
                if ($user->user_type == 4) {
                    $extra = [
                        'payable_company' => $payable,
                        'payable_address' => $payableAddress,
                        'payable_address1' => $payableAddress1,
                        'payable_city' => $payableCity,
                        'payable_state' => $payableState,
                        'payable_zip' => $payableZip,
                        'new_construction_expert' => $constructionExpert,
                        'payment_email_notification' => $paymentEmailNotification,
                        'payment_sms_notification' => $paymentSMSNotification,
                        'accept_cod' => $acceptCod,
                        'is_allowed_license_bypass' => $bypassLicense,
                        'is_priority_appr' => $isPriority,
                        'software_charge' => $softwareFee,
                        'is_auto_select_priority' => $isAutoSelectPriority,
                        'is_in_house' => $isInHouse,
                        'is_zero_fee' => $isZeroFee,
                        'appr_state_compliance_approved' => $stateCompliance,
                        'exclude' => $exclude,
                        'capacity' => $capacity,
                        'ein' => preg_replace('/[^0-9]/', '', $ein),
                        'pos_lat' => $posLat,
                        'pos_long' => $posLong,
                        'tax_class' => $taxClass,
                        'ins_company' => $insuranceCompany,
                        'ins_amt' => $insuranceAmount,
                        'ins_amt_agg' => $insuranceAmountAgg,
                        'ins_expire' => $insuranceExpire,
                        'fha' => $isFHA,
                        'is_away' => $isAway,
                        'daily_digest' => $isDigest,
                        'away_start_date' => $awayStartDate ? strtotime($awayStartDate) : 0,
                        'away_end_date' => $awayEndDate ? strtotime($awayEndDate) : 0,
                        'phone_type' => $phoneType ? $phoneType : 0,
                        'phone_provider' => $phoneProvider ? $phoneProvider : 0,
                        'autoselect_enabled' => $autoSelectEnabled,
                        'has_background_check' => $hasBackgroundCheck,
                        'background_check_date' => $backroundCheckDate ? strtotime($backroundCheckDate) : 0,
                        'appr_software' => $apprSoftware,
                        'enable_text_invites' => $enableTextInvites,
                    ];

                    $update = array_merge($extra, $update);

                    // See if we need to create a record or insert one

                    $user->userData()->update($update);
                    if ($addTaxEntry) {
                        $this->service->recordVendorTaxChange($user, $user->ein, $user->tax_class, $user->payable_company);
                    }
                } elseif ($user->user_type == 14) {
                    $extra = [
                        'payable_company' => $payable,
                        'payable_address' => $payableAddress,
                        'payable_address1' => $payableAddress1,
                        'payable_city' => $payableCity,
                        'payable_state' => $payableState,
                        'payable_zip' => $payableZip,
                        'exclude' => $exclude,
                        'capacity' => $capacity,
                        'ein' => preg_replace('/[^0-9]/', '', $ein),
                        'pos_lat' => $posLat,
                        'pos_long' => $posLong,
                        'tax_class' => $taxClass,
                        'license_state' => $licenseState,
                        'license_num' => $licenseNumber,
                        'license_exp_date' => $licenseExpire,
                        'phone_type' => $phoneType,
                        'phone_provider' => $phoneProvider,
                    ];

                    $update = array_merge($extra, $update);

                    // See if we need to create a record or insert one
                    $user->userData()->update($update);

                    if ($addTaxEntry) {
                        $this->service->recordVendorTaxChange($user, $user->ein, $user->tax_class, $user->payable_company);
                    }
                } else {
                    $extra = [];

                    // Sales
                    if (userIsSalesPerson($user)) {
                        $extra['margin_minimum'] = $minimumMargin;
                        $extra['al_margin_minimum'] = $alminimumMargin;

                        $extra['comission'] = $comission;
                        $extra['al_comission'] = $alcomission;

                        $this->service->updateClientsBySalesId($user->id, [
                            'salesid_com' => $comission,
                            'salesid_alt_com' => $alcomission
                        ]);
                        $this->service->updateClientsBySalesId2($user->id, [
                            'salesid2_com' => $comission,
                            'salesid2_alt_com' => $alcomission
                        ]);

                    }

                    $update = array_merge($update, $extra);

                    // See if we need to create a record or insert one
                    $user->userData()->update($update);
                }

                $this->service->updateAppraiserFHAStateApproved($user);
            } catch (\Exception $e) {
                $error = true;
                $errors[] = $e->getMessage();
            }

            if (in_array($user->user_type, [User::USER_TYPE_APPRAISER, User::USER_TYPE_REAL_ESTATE_AGENT])) {
                $this->service->saveCommunicationMethods($user->id, $request->post('communicationMethods'));
                $this->service->saveBusinessHours($user->id, $request->post('businessHours'));
            }

            // Permissions
            if (checkPermission($adminPermissionCategory, 'can_manage_user_client_permissions')) {
                $this->service->removePermissions($user->id);
                $this->service->savePermissions($user->id, $request->post('userpermissions'));
            }
            if (!$error) {
                // set flash
                return redirect(route('admin.users.show', [$user->id]))
                    ->with('success', 'User Changes Saved.');
            } else {
                return redirect(route('admin.users.show', [$user->id]))
                    ->with('error', implode('<br />', $errors));
            }
        } else {
            return redirect(route('admin.users.show', [$user->id]))
                ->with('error', implode('<br />', $errors));
        }
    }

    public function addNote(User $user, UserAddNoteRequest $request)
    {
        $note = $request->post('note');
        $insertData = [
            'userid' => $user->id,
            'adminid' => getUserId(),
            'notes' => $note,
            'dts' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        // Add note
        $this->service->insertUserNotes($insertData);
        return response()->json([
            'html' => view('admin::users.partials.user_notes', [
                'notes' => $user->userNotes,
                'adminPermissionCategory' => new AdminPermissionCategory(),
                'user' => $user
            ])->render(),
        ]);
    }

    public function loadUserLogs(User $user)
    {
        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        return response()->json([
            'html' => view('admin::users.partials.user_log', [
                'user' => $user,
                'logs' => $this->service->getUserOrderLogsWithLimit($user->id, 1),
            ])->render(),
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserLogs(User $user)
    {
        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }
        $user->orderLogs()->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function getEmailTemplate(User $user, Request $request)
    {
        $id = $request->post('templateId');
        if (!$id) {
            return response()->json([
                'error' => 'Sorry, that template was not found.'
            ]);
        }

        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        // Load template
        $info = $this->service->getEmailTemplate($id);
        if (!$info) {
            return response()->json([
                'error' => 'Sorry, that template was not found.'
            ]);
        }

        $order = $this->service->getOrderByAcceptedBy($user->id);

        $html = $this->service->convertOrderKeysToValues($info->content, $order);

        // Return
        return response()->json([
            'html' => $html
        ]);
    }

    public function sendEmail(User $user, Request $request)
    {
        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        $subject = $request->post('subject');
        $message = $request->post('message');

        if (strpos($message, '{signature}') !== false) {
            $message = str_replace('{signature}', getUser()->email_signature, $message);
        }

        dispatch(new SendUserEmail($subject, $message, $user->email));

        $this->service->insertUserNotes([
            'userid' => $user->id,
            'adminid' => getUserId(),
            'notes' => $subject,
            'message' => $message,
            'dts' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function addCard(User $user, Request $request)
    {
        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        $ccInfo = $request->post('ccInfo');

        $data = [
            'name' => trim($ccInfo['cc_firstname'] . ' ' . $ccInfo['cc_lastname']),
            'number' => $ccInfo['cc_number'],
            'exp' => ($ccInfo['cc_exp_month'] . $ccInfo['cc_exp_year']),
            'amount' => 0,
            'cvv' => $ccInfo['cc_cvv'],
            'zip' => $ccInfo['cc_zip'],
            'address' => $ccInfo['cc_address'],
            'city' => $ccInfo['cc_city'],
            'state' => $ccInfo['cc_state'],
        ];

        $profile = $this->service->addCard($user, $data);
        // Add Card Info as log
        $creditLog = sprintf("Credit Card Authorized. %s (%s)", $data['name'], StringHelper::maskCreditCard(StringHelper::formatCreditCard($data['number'])));
        // Add user note
        $userNote = new UserNote();
        $userNote->userid = $user->id;
        $userNote->adminid = getUserId();
        $userNote->notes = $creditLog;
        $userNote->dts = Carbon::now()->format('Y-m-d H:i:s');
        $userNote->save();
        return response()->json([
            'html' => view('admin::users.partials.current_cc_card', [
                'user' => $user,
                'profile' => $profile
            ])->render()
        ]);
    }

    public function resetPasswordLink(User $user)
    {
        if (!$user) {
            return redirect(route('admin.users.index'))
                ->with('error', 'Sorry, that user was not found.');
        }

        $send = $this->service->resetPasswordEmail($user);

        $userNote = new UserNote();
        $userNote->userid = $user->id;
        $userNote->adminid = getUserId();
        $userNote->notes = sprintf('Sent User Password Reset Email. Result: %s', $send === true ? 'OK' : $send);
        $userNote->dts = Carbon::now()->format('Y-m-d H:i:s');
        $userNote->save();

        if (!$send) {
            return redirect(route('admin.users.index'))
                ->with('error', 'Email does not sent');
        }

        return redirect(route('admin.users.show', [$user->id]))
            ->with('success', 'Password Link Sent.');
    }

    public function backgroundCheckUpload(User $user, Request $request)
    {
        $file = $request->file('backgroundfile');
        if(!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        if(!$file) {
            return response()->json([
                'error' => 'Sorry, No file was uploaded.'
            ]);
        }

        $type = UserDoc::TYPE_BACKGROUND_CHECK;
        $this->service->uploadUserDocument($user, $file, $type);

        $backgroundDocument = $this->service->getUserBackgroundCheckDocument($user->id);

        $html = view('admin::users.partials.background_row', [
            'user' => $user,
            'backgroundDocument' => $backgroundDocument,
            'backgroundLink' => $this->service->getUserDocumentLink($backgroundDocument),
        ])->render();
        return response()->json(['html' => $html]);
    }

    /**
     * @param UserDoc $row
     * @return \Illuminate\Http\RedirectResponse
     */
    public function documentDownload($id, $kind)
    {
        $row = null;
        switch ($kind) {
            case 'background':
                $row = UserDoc::find($id);
                break;
            case 'additional':
                $row = UserDocument::find($id);
                break;
        }
        if (!$row) {
            return redirect(route('admin.users.index'))
                ->with('error', 'Sorry, that document was not found.');
        }

        // Get document from s3
        try {
            return $this->service->downloadFileFromS3('/user_documents/' . $row->userid . '/' . $row->filename);
        } catch (\Exception $e) {
            return redirect(route('admin.users.show', [$row->userid]))
                ->with('error', 'Error! File not found. ' . $e->getMessage());
        }
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function additionalDocument(User $user, Request $request)
    {
        $type = $request->post('documentType');
        $file = $request->file('additional-document');

        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        if (!$type) {
            return response()->json([
                'error' => 'Sorry, You must select a type.'
            ]);
        }

        if (!$file || !$file->getClientOriginalName()) {
            return response()->json([
                'error' => 'Sorry, File was not uploaded'
            ]);
        }

        $this->service->uploadUserDocument($user, $file, $type, 'additional');

        return response()->json([
            'html' => view('admin::users.partials.additional_docs', [
                'user' => $user,
                'additionalDocuments' => $this->service->getAdditionalDocuments($user->id),
            ])->render()
        ]);
    }

    /**
     * @param UserDocument $row
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function documentView(UserDocument $row)
    {
        if (!$row) {
            return redirect(route('admin.users.index'))
                ->with('error', 'Sorry, that document was not found.');
        }

        $object = null;
        // Get document from s3
        try {
            return  $this->service->getFileFromS3('/user_documents/' . $row->userid . '/' . $row->filename);
        } catch (\Exception $e) {
            return redirect(route('admin.users.show', [$row->userid]))
                ->with('error', 'Error! File not found. ' . $e->getMessage());
        }
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eandoUpload(User $user, Request $request)
    {
        $file = $request->file('eandofile');
        if (!$user) {
            return response()->json([
                'error' => 'Sorry, That user was not found.'
            ]);
        }

        if (!$file || !$file->getClientOriginalName()) {
            return response()->json([
                'error' => 'Sorry, File was not uploaded'
            ]);
        }

        $this->service->uploadUserDocument($user, $file, 'ins');
        $eandoDocument = $this->service->getUserInsDocument($user->id);
        return response()->json([
            'html' => view('admin::users.partials.eando_row', [
                'user' => $user,
                'eandoDocument' => $eandoDocument,
                'eandoLink' => $this->service->getUserDocumentLink($eandoDocument),
            ])->render()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
