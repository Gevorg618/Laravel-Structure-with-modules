<?php

namespace Modules\Admin\Services;


use App\Jobs\SendResetPasswordEmail;
use App\Mail\ResetPasswordMail;
use App\Models\Appraisal\AltOrder;
use App\Models\Appraisal\Cert;
use App\Models\Appraisal\Order;
use App\Models\Documents\UserDoc;
use App\Models\Documents\UserDocument;
use App\Models\Tools\Setting;
use App\Models\User;
use App\Models\UserFdProfile;
use App\Services\CreateS3Storage;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Repositories\AdminGroupRepository;
use Modules\Admin\Repositories\AdminTeamsRepository;
use Modules\Admin\Repositories\AgentPaymentRepository;
use Modules\Admin\Repositories\AltOrderProductRelationRepository;
use Modules\Admin\Repositories\AltOrderProductTypeRepository;
use Modules\Admin\Repositories\AltOrderRepository;
use Modules\Admin\Repositories\AltSubOrderRepository;
use Modules\Admin\Repositories\ApiUserRepository;
use Modules\Admin\Repositories\Appraisal\ApprPriorityInvitesRepository;
use Modules\Admin\Repositories\Appraisal\CertRepository;
use Modules\Admin\Repositories\Appraisal\FHALisencesRepository;
use Modules\Admin\Repositories\Appraisal\LoanReasonRepository;
use Modules\Admin\Repositories\Appraisal\LoanTypesRepository;
use Modules\Admin\Repositories\Appraisal\OrderDelayCodeRepository;
use Modules\Admin\Repositories\Appraisal\OrderDelayDatesRepository;
use Modules\Admin\Repositories\Appraisal\OrderInvitesRepository;
use Modules\Admin\Repositories\Appraisal\TypesRepository;
use Modules\Admin\Repositories\AppraiserGroupsRepository;
use Modules\Admin\Repositories\AppraiserPaymentRepository;
use Modules\Admin\Repositories\ApprEmailRulesRepository;
use Modules\Admin\Repositories\ApprQcAnswersRepository;
use Modules\Admin\Repositories\ApprQcOrderCorrectionsRepository;
use Modules\Admin\Repositories\ApprQcRealviewOrderRepository;
use Modules\Admin\Repositories\ApprQcRepository;
use Modules\Admin\Repositories\ApprReferenceRepository;
use Modules\Admin\Repositories\ApprUwAnswersRepository;
use Modules\Admin\Repositories\ApprUwRepository;
use Modules\Admin\Repositories\AscDataRepository;
use Modules\Admin\Repositories\Clients\ClientRepository;
use Modules\Admin\Repositories\EmailTemplatesRepository;
use Modules\Admin\Repositories\LanguageRepository;
use Modules\Admin\Repositories\ManagerReport\UserGeneratorRepository;
use Modules\Admin\Repositories\OrderLogRepository;
use Modules\Admin\Repositories\OrderRepository;
use Modules\Admin\Repositories\PhoneProviderRepository;
use Modules\Admin\Repositories\PhoneTypeRepository;
use Modules\Admin\Repositories\PreferApprRepository;
use Modules\Admin\Repositories\QcStatsRepository;
use Modules\Admin\Repositories\StateComplianceRepository;
use Modules\Admin\Repositories\SupervisingUsersRepository;
use Modules\Admin\Repositories\Tiger\AmcRepository;
use Modules\Admin\Repositories\UserActivityRecordRepository;
use Modules\Admin\Repositories\UserApprTypeRepository;
use Modules\Admin\Repositories\UserAscLicenseRepository;
use Modules\Admin\Repositories\UserBusibessHourRepository;
use Modules\Admin\Repositories\UserClientPermissionRepository;
use Modules\Admin\Repositories\UserCommunicationPreferenceRepository;
use Modules\Admin\Repositories\UserDataAppraiserSplitRepository;
use Modules\Admin\Repositories\UserDocsRepository;
use Modules\Admin\Repositories\UserDocumentsRepository;
use Modules\Admin\Repositories\UserDocumentTypesRepository;
use Modules\Admin\Repositories\UserFdProfileRepository;
use Modules\Admin\Repositories\UserFhaStateApprovedRepository;
use Modules\Admin\Repositories\UserGroupRepository;
use Modules\Admin\Repositories\UserGroupsRepository;
use Modules\Admin\Repositories\UserLanguageRepository;
use Modules\Admin\Repositories\UserLoanPurposeRepository;
use Modules\Admin\Repositories\UserLoanTypeRepository;
use Modules\Admin\Repositories\UserNotesRepository;
use Modules\Admin\Repositories\UserRepository;
use Modules\Admin\Repositories\UserTemplatesRepository;
use Modules\Admin\Repositories\UserTypesRepository;
use Modules\Admin\Repositories\VendorRepository;

class UsersService
{
    protected $userTypesRepo;

    protected $userRepo;

    protected $docsRepo;

    protected $userGroupRepo;

    protected $apprGroupRepo;

    protected $clientsRepo;

    protected $userDocumentTypesRepo;

    protected $userDocumentsRepo;

    protected $fhaLicencesRepo;

    protected $fhaStateApprovedRepo;

    protected $ascDataRepository;

    protected $userAscLicenseRepo;

    protected $stateComplianceRepo;

    protected $phoneTypeRepo;

    protected $phoneProviderRepo;

    protected $userLanguageRepo;

    protected $languageRepo;

    protected $userApprTypeRepo;

    protected $apprTypesRepo;

    protected $userLoanTypeRepo;

    protected $loanTypesRepo;

    protected $userLoanPurposeRepo;

    protected $loanReasonRepo;

    protected $apprReferenceRepo;

    protected $userDataAppraiserSplitRepo;

    protected $agentPaymentRepo;

    protected $appraiserPaymentRepo;

    protected $altOrderRepo;

    protected $altSubOrderRepo;

    protected $orderRepo;

    protected $orderInvitesRepo;

    protected $userCommunicationPreferenceRepo;

    protected $userBusinessHourRepo;

    protected $orderDelayDatesRepo;

    protected $orderDelayCodeRepo;

    protected $orderLogRepo;

    protected $altOrderProductTypeRepo;

    protected $altOrderProductRelationRepo;

    protected $userNotesRepo;

    protected $apprQcRepo;

    protected $apprQcAnswerRepo;

    protected $apprQcOrderCorrectionRepo;

    protected $apprUwRepo;

    protected $apprUwAnswersRepo;

    protected $qcStatsRepo;

    protected $userGeneratorRepo;

    protected $preferApprRepo;

    protected $emailTemplatesRepo;

    protected $userTemplatesRepo;

    protected $userFdProfileRepo;

    protected $userActivityRecordRepo;

    protected $amcRepo;

    protected $apiUserRepo;

    protected $adminGroupRepo;

    protected $tigerService;

    protected $apprPriorityInvitesRepo;

    protected $apprEmailRulesRepo;

    protected $certRepo;

    protected $userGroupsRepo;

    protected $supervisingUserRepo;

    protected $vendorRepo;

    protected $userClientPermissionRepo;

    protected $apprQcRealviewOrderRepo;

    protected $storage;

    protected $adminTeamsRepo;

    const IRS147C_FORM_TYPE = 'IRS147C';

    const APPR_QC_TYPE_REALVIEW_HTML = 'realviewhtml';

    /**
     * UsersService constructor.
     * @param $userTypesRepo
     */
    public function __construct(
        UserTypesRepository $userTypesRepo,
        UserRepository $userRepo,
        UserDocsRepository $docsRepo,
        AppraiserGroupsRepository $apprGroupRepo,
        UserGroupRepository $userGroupRepo,
        ClientRepository $clientRepo,
        UserDocumentTypesRepository $documentTypesRepo,
        UserDocumentsRepository $userDocumentsRepo,
        FHALisencesRepository $fhaLisencesRepository,
        UserFhaStateApprovedRepository $fhaStateApprovedRepo,
        AscDataRepository $ascDataRepository,
        UserAscLicenseRepository $userAscLicenseRepo,
        StateComplianceRepository $stateComplianceRepo,
        PhoneTypeRepository $phoneTypeRepo,
        PhoneProviderRepository $phoneProviderRepo,
        UserLanguageRepository $userLanguageRepo,
        LanguageRepository $languageRepo,
        UserApprTypeRepository $userApprTypeRepo,
        TypesRepository $typesRepo,
        UserLoanTypeRepository $userLoanTypeRepo,
        LoanTypesRepository $loanTypesRepo,
        UserLoanPurposeRepository $userLoanPurposeRepo,
        LoanReasonRepository $loanReasonRepo,
        ApprReferenceRepository $apprReferenceRepo,
        UserDataAppraiserSplitRepository $userDataAppraiserSplitRepo,
        AgentPaymentRepository $agentPaymentRepo,
        AppraiserPaymentRepository $appraiserPaymentRepo,
        AltOrderRepository $altOrderRepo,
        AltSubOrderRepository $altSubOrderRepo,
        OrderRepository $orderRepo,
        OrderInvitesRepository $orderInvitesRepo,
        UserCommunicationPreferenceRepository $userCommunicationPreferenceRepo,
        UserBusibessHourRepository $userBusibessHourRepo,
        OrderDelayDatesRepository $orderDelayDatesRepo,
        OrderDelayCodeRepository $orderDelayCodeRepo,
        OrderLogRepository $orderLogRepo,
        AltOrderProductTypeRepository $altOrderProductTypeRepo,
        AltOrderProductRelationRepository $altOrderProductRelationRepo,
        UserNotesRepository $userNotesRepo,
        ApprQcRepository $apprQcRepo,
        ApprQcAnswersRepository $apprQcAnswersRepo,
        ApprQcOrderCorrectionsRepository $apprQcOrderCorrectionsRepository,
        ApprUwRepository $apprUwRepo,
        ApprUwAnswersRepository $apprUwAnswersRepo,
        QcStatsRepository $qcStatsRepo,
        UserGeneratorRepository $userGeneratorRepo,
        PreferApprRepository $preferApprRepo,
        EmailTemplatesRepository $emailTemplatesRepo,
        UserTemplatesRepository $userTemplatesRepo,
        UserFdProfileRepository $userFdProfileRepo,
        UserActivityRecordRepository $userActivityRecordRepo,
        AmcRepository $amcRepo,
        ApiUserRepository $apiUserRepo,
        AdminGroupRepository $adminGroupRepo,
        TigerService $tigerService,
        ApprPriorityInvitesRepository $apprPriorityInvitesRepo,
        ApprEmailRulesRepository $apprEmailRulesRepo,
        CertRepository $certRepo,
        UserGroupsRepository $userGroupsRepo,
        SupervisingUsersRepository $supervisingUserRepo,
        VendorRepository $vendorRepo,
        UserClientPermissionRepository $userClientPermissionRepo,
        ApprQcRealviewOrderRepository $apprQcRealviewOrderRepo,
        CreateS3Storage $storage,
        AdminTeamsRepository $adminTeamsRepository
    )
    {
        $this->userTypesRepo = $userTypesRepo;
        $this->userRepo = $userRepo;
        $this->docsRepo = $docsRepo;
        $this->apprGroupRepo = $apprGroupRepo;
        $this->userGroupRepo = $userGroupRepo;
        $this->clientsRepo = $clientRepo;
        $this->userDocumentTypesRepo = $documentTypesRepo;
        $this->userDocumentsRepo = $userDocumentsRepo;
        $this->fhaLicencesRepo = $fhaLisencesRepository;
        $this->fhaStateApprovedRepo = $fhaStateApprovedRepo;
        $this->ascDataRepository = $ascDataRepository;
        $this->userAscLicenseRepo = $userAscLicenseRepo;
        $this->stateComplianceRepo = $stateComplianceRepo;
        $this->phoneTypeRepo = $phoneTypeRepo;
        $this->phoneProviderRepo = $phoneProviderRepo;
        $this->userLanguageRepo = $userLanguageRepo;
        $this->languageRepo = $languageRepo;
        $this->userApprTypeRepo = $userApprTypeRepo;
        $this->apprTypesRepo = $typesRepo;
        $this->userLoanTypeRepo = $userLoanTypeRepo;
        $this->loanTypesRepo = $loanTypesRepo;
        $this->userLoanPurposeRepo = $userLoanPurposeRepo;
        $this->loanReasonRepo = $loanReasonRepo;
        $this->apprReferenceRepo = $apprReferenceRepo;
        $this->userDataAppraiserSplitRepo = $userDataAppraiserSplitRepo;
        $this->agentPaymentRepo = $agentPaymentRepo;
        $this->appraiserPaymentRepo = $appraiserPaymentRepo;
        $this->altOrderRepo = $altOrderRepo;
        $this->altSubOrderRepo = $altSubOrderRepo;
        $this->orderRepo = $orderRepo;
        $this->orderInvitesRepo = $orderInvitesRepo;
        $this->userCommunicationPreferenceRepo = $userCommunicationPreferenceRepo;
        $this->userBusinessHourRepo = $userBusibessHourRepo;
        $this->orderDelayDatesRepo = $orderDelayDatesRepo;
        $this->orderDelayCodeRepo = $orderDelayCodeRepo;
        $this->orderLogRepo = $orderLogRepo;
        $this->altOrderProductTypeRepo = $altOrderProductTypeRepo;
        $this->altOrderProductRelationRepo = $altOrderProductRelationRepo;
        $this->userNotesRepo = $userNotesRepo;
        $this->apprQcRepo = $apprQcRepo;
        $this->apprQcAnswerRepo = $apprQcAnswersRepo;
        $this->apprQcOrderCorrectionRepo = $apprQcOrderCorrectionsRepository;
        $this->apprUwRepo = $apprUwRepo;
        $this->apprUwAnswersRepo = $apprUwAnswersRepo;
        $this->qcStatsRepo = $qcStatsRepo;
        $this->userGeneratorRepo = $userGeneratorRepo;
        $this->preferApprRepo = $preferApprRepo;
        $this->emailTemplatesRepo = $emailTemplatesRepo;
        $this->userTemplatesRepo = $userTemplatesRepo;
        $this->userFdProfileRepo = $userFdProfileRepo;
        $this->userActivityRecordRepo = $userActivityRecordRepo;
        $this->amcRepo = $amcRepo;
        $this->apiUserRepo = $apiUserRepo;
        $this->adminGroupRepo = $adminGroupRepo;
        $this->tigerService = $tigerService;
        $this->apprPriorityInvitesRepo = $apprPriorityInvitesRepo;
        $this->apprEmailRulesRepo = $apprEmailRulesRepo;
        $this->certRepo = $certRepo;
        $this->userGroupsRepo = $userGroupsRepo;
        $this->supervisingUserRepo = $supervisingUserRepo;
        $this->vendorRepo = $vendorRepo;
        $this->userClientPermissionRepo = $userClientPermissionRepo;
        $this->apprQcRealviewOrderRepo = $apprQcRealviewOrderRepo;
        $this->storage = $storage;
        $this->adminTeamsRepo = $adminTeamsRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUserTypes()
    {
        return $this->userTypesRepo->getUserTypes();
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsers($data = [])
    {
        return $this->userRepo->getUsers($data);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserInsDocument($userId)
    {
        return $this->docsRepo->getUserInsDocument($userId);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserStateLicenses($userId)
    {
        return Cert::where('user_id', $userId)
            ->orderBy('state')->get();
    }

    /**
     * @param $userId
     * @param $type
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserDocumentByType($userId, $type)
    {
        return UserDocument::where('userid', $userId)
            ->whereHas('userDocumentType', function ($query) use ($type) {
                return $query->where('code', $type);
            })->latest('id')->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAppraiserGroupByUserId($id)
    {
        return $this->apprGroupRepo->getAppraiserGroupByUserId($id);
    }

    public function setAlerts(User $user)
    {
        $alerts = [];
        if ($user->user_type == 4) {
            if (!$user->ins_expire || $user->ins_expire == '0000-00-00') {
                $alerts[] = "E&O Expiration date is not set.";
            } elseif ($user->ins_expire && strtotime($user->ins_expire) <= time()) {
                $alerts[] = "E&O Expiration date appears to be outdated.";
            }

            $eandoDocument = $this->getUserInsDocument($user->id);
            if (!$eandoDocument) {
                $alerts[] = "E&O Document is missing.";
            }

            $stateLicenses = $this->getAppraiserStateLicenses($user->id);
            if ($stateLicenses) {
                foreach ($stateLicenses as $r) {
                    if (!$r->cert_expire || $r->cert_expire == '0000-00-00') {
                        $alerts[] = sprintf("%s State License Expiration date is not set.", $r->state);
                    } elseif ($r->cert_expire && strtotime($r->cert_expire) <= time()) {
                        $alerts[] = sprintf("%s State License appears to be outdated.", $r->state);
                    }
                }
            } else {
                $alerts[] = sprintf('There are no state licenses set.');
            }

            if (!$user->ein) {
                $alerts[] = "SSN / EIN Is Missing.";
            }

            if (!$user->tax_class) {
                $alerts[] = "User Tax Classification is missing.";
            }

            $irsForm = $this->getUserDocumentByType($user->id, self::IRS147C_FORM_TYPE);
            if (!$irsForm) {
                $alerts[] = "User IRS 147C Document Is Missing.";
            }
        }

        if ($user->user_type == 14) {
            if (!$user->license_exp_date || $user->license_exp_date == '0000-00-00') {
                $alerts[] = "License Expiration date is not set.";
            } elseif ($user->license_exp_date && strtotime($user->license_exp_date) <= time()) {
                $alerts[] = "License Expiration date appears to be outdated.";
            }
        }
        return $alerts;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUserGroups()
    {
        return $this->userGroupRepo->getUserGroups();
    }

    /**
     * @return array
     */
    public function getUserSuffixList()
    {
        $list = Setting::getSetting('user_suffix_list');
        $items = [];

        if ($list) {
            $list = explode("\n", $list);
            foreach ($list as $i) {
                $row = explode('=', $i);
                if (count($row) > 1) {
                    $items[$row[0]] = $row[1];
                } else {
                    $items[$row[0]] = $row[0];
                }
            }
        }

        return $items;
    }

    /**
     * @return \Modules\Admin\Repositories\Clients\collection
     */
    public function getClients()
    {
        return $this->clientsRepo->clients()->pluck('descrip', 'id');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function getWholeSaleManagerLenderId(User $user)
    {
        return $user->lenders ? $user->lenders->first() : false;
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserBackgroundCheckDocument($userId)
    {
        return $this->docsRepo->getUserBackgroundCheckDocument($userId);
    }

    public function getUserDocumentLink($file, $kind = 'background')
    {

        if ($file) {
            return route('admin.users.document-download', [$file->id, $kind]);
        }
        return false;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUserDocumentTypes()
    {
        return $this->userDocumentTypesRepo->getUserDocumentTypes();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAdditionalDocuments($userId)
    {
        return $this->userDocumentsRepo->getAdditionalDocuments($userId);
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getUserOldHUDLicenses(User $user)
    {
        $currentLicenses = $this->getAppraiserStateLicenses($user->id);
        $currentLicensesList = [];
        if ($currentLicenses) {
            foreach ($currentLicenses as $currentLicense) {
                $currentLicensesList[] = $currentLicense->state . $currentLicense->cert_num;
            }
        }
        return $this->fhaLicencesRepo->getLisencesForUserDocs($user, $currentLicensesList);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserCachedFHALicenses($id)
    {
        return $this->fhaStateApprovedRepo->getAppraiserCachedFHALicenses($id);
    }

    /**
     * @param User $user
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserOldASCLicenses(User $user)
    {
        $currentLicenses = $this->getAppraiserStateLicenses($user->id);
        $currentLicensesList = [];
        if ($currentLicenses) {
            foreach ($currentLicenses as $currentLicense) {
                $currentLicensesList[] = $currentLicense->cert_num;
            }
        }

        $rows = $this->ascDataRepository->getAscLicenses($user, $currentLicensesList);

        // Check if that user has any HUD licenses
        $licenses = $this->getUserHUDLicenses($user);

        // Check if one of the licenses is for the state we need
        if ($licenses) {
            foreach ($licenses as $row) {
                $number = substr($row->license_number, 2);
                $record = $this->ascDataRepository->getLicenseByNumberAndNames($user, $number);
                if ($record) {
                    $rows[] = $record;
                }
            }
        }
        return $rows;
    }

    public function getUserHUDLicenses(User $user)
    {
        $licenses = $this->fhaStateApprovedRepo->getAppraiserCachedFHALicenses($user->id);
        $rows = false;

        if ($licenses) {
            $list = $licenses->pluck('license_number')->toArray();
            $rows = $this->fhaLicencesRepo->getLicensesByLicenseNumbers($list);
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function getAppraiserLicensesTypes()
    {
        return [
            1 => 'Licensed',
            2 => 'Certified General',
            3 => 'Certified Residential',
            4 => 'Transitional License',
        ];
    }

    /**
     * @param $k
     * @return mixed
     */
    public function getAppraiserLicenseTypeName($k)
    {
        $types = $this->getAppraiserLicensesTypes();
        return $types[$k] ?? $k;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserCachedASCLicenses($id)
    {
        return $this->userAscLicenseRepo->getAppraiserCachedASCLicenses($id);
    }

    /**
     * @param $userId
     * @param $state
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserStateDocument($userId, $state)
    {
        return $this->docsRepo->getUserStateDocument($userId, $state);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserLicenseDocument($userId)
    {
        return $this->docsRepo->getUserLicenseDocument($userId);
    }

    /**
     * @return array
     */
    public function getUserTaxClasses()
    {
        return [
            'sole' => 'Sole Proprietor/ Individual - SSN',
            'soleein' => 'Sole Proprietor/ Individual - EIN',
            'ccorp' => 'C-Corp',
            'scorp' => 'S-Corp',
            'partnership' => 'Partnership',
            'trust' => 'Trust',
            'llc' => 'LLC',
            'llc-c' => 'LLC - C Corporation',
            'llc-s' => 'LLC - S Corporation',
            'llc-p' => 'LLC - Partnership',
            'quickbooksssn' => 'QuickBooks SSN',
            'quickbooksein' => 'QuickBooks EIN',
        ];
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserW9Document($userId)
    {
        return $this->docsRepo->getUserW9Document($userId);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getUserDocuments($userId)
    {
        return $this->docsRepo->getUserDocuments($userId);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getStateComplianceTakenStates()
    {
        return $this->stateComplianceRepo->getStateComplianceTakenStates();
    }

    /**
     * @param $exclude
     * @return string
     */
    public function getUserExcludeTitle($exclude)
    {
        switch ($exclude) {
            case 'Y':
                return 'Yes';

            case 'P':
                return 'Pending';

            case 'N':
            default:
                return 'No';
        }
    }

    /**
     * @return array
     */
    public function getAppraisalSoftwareList()
    {
        $list = Setting::getSetting('user_signup_appr_software_list');
        $items = [];

        if ($list) {
            $list = explode("\n", $list);
            foreach ($list as $i) {
                $row = explode('=', $i);
                if (count($row) > 1) {
                    $items[$row[0]] = $row[1];
                } else {
                    $items[$row] = $row;
                }
            }
        }

        return $items;
    }

    public function excludeOptions()
    {
        return ['N' => 'No', 'Y' => 'Yes', 'P' => 'Under Review', 'W' => 'Watch'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPhoneTypes()
    {
        return $this->phoneTypeRepo->getPhoneTypes();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPhoneProviders()
    {
        return $this->phoneProviderRepo->getPhoneProviders();
    }

    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedLanguages($userId)
    {
        return $this->userLanguageRepo->getUserSelectedLanguages($userId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLanguages()
    {
        return $this->languageRepo->getLanguages();
    }

    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedApprTypes($userId)
    {
        return $this->userApprTypeRepo->getUserSelectedApprTypes($userId);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getApprTypeList()
    {
        return $this->apprTypesRepo->getTypesForMultiSelect();
    }

    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedLoanTypes($userId)
    {
        return $this->userLoanTypeRepo->getUserSelectedLoanTypes($userId);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getLoanTypeList()
    {
        return $this->loanTypesRepo->getLoanTypeList();
    }

    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedLoanPurposes($userId)
    {
        return $this->userLoanPurposeRepo->getUserSelectedLoanPurposes($userId);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getLoanPurposeList()
    {
        return $this->loanReasonRepo->getLoanPurposeList();
    }

    /**
     * @param $userId
     * @return array
     */
    public function getUserReferrences($userId)
    {
        return $this->apprReferenceRepo->getUserReferrences($userId);
    }

    /**
     * @param $i
     * @return string
     */
    public function getReferrenceNameByNumber($i)
    {
        switch ($i) {
            case 1:
                return 'AMC Reference #1';
            case 2:
                return 'AMC Reference #2';
            case 3:
                return 'Other Reference';
            default:
                return 'Reference';
        }
    }

    /**
     * @param $userId
     * @return Collection
     */
    public function getAppraiserSplitValuesById($userId)
    {
        return $this->userDataAppraiserSplitRepo->getAppraiserSplitValuesById($userId);
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function getPayments(User $user)
    {
        if ($user->user_type == 14) {
            return $this->agentPaymentRepo->getAgentCheckPaymentsById($user->id);
        }
        return $this->appraiserPaymentRepo->getAppraiserCheckPaymentsById($user->id);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getALOrderInfo($id)
    {
        return $this->altOrderRepo->findById($id);
    }

    /**
     * @param $id
     * @param $agentId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getAgentByIdSubOrder($id, $agentId)
    {
        return $this->altSubOrderRepo->getAgentByIdSubOrder($id, $agentId);
    }

    /**
     * @param $id
     * @return \App\Models\Appraisal\Order
     */
    public function getApprOrderById($id)
    {
        return $this->orderRepo->getOrder($id);
    }

    /**
     * @param $acceptedby
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getOrderByAcceptedBy($acceptedby)
    {
        return $this->orderRepo->getOrderByAcceptedBy($acceptedby);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function getOrderAppraiserAssignmentInvitesByApprIdAdmin($id)
    {
        return $this->orderInvitesRepo
            ->getOrderAppraiserAssignmentInvitesByApprIdAdmin($id);
    }

    /**
     * @param $userId
     * @return array
     */
    public function getAppraiserInviteCounts($userId)
    {
        return $this->orderInvitesRepo->getAppraiserInviteCounts($userId);
    }

    /**
     * @param $yes
     * @return string
     */
    public function getOrderDocumentYesNoImage($yes)
    {
        if ($yes) {
            return "<img src='/images/icons/famfamfam/tick.png' alt='Yes' />";
        }
        return "<img src='/images/icons/famfamfam/cross.png' alt='No' />";
    }

    /**
     * @return array
     */
    public function communicationTypes()
    {
        return [
            'email' => 'Email',
            'phone' => 'Phone',
            'text' => 'Text Messages'
        ];
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSelectedCommunicationMethods($id)
    {
        return $this->userCommunicationPreferenceRepo->getSelectedCommunicationMethods($id);
    }

    /**
     * @return array
     */
    public function daysOfOperation()
    {
        return [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];
    }

    /**
     * @return array
     */
    public function hoursOfOperation()
    {
        $rows = ['n/a' => '- Not Working -'];
        $start = 5;
        $end = 23;
        for ($i = $start; $i <= $end; $i++) {
            $date = strtotime(date('Y-m-d ' . $i . ':00'));
            $rows[date('H:i', $date)] = date('g:i A', $date);
        }

        return $rows;
    }

    /**
     * @param $id
     * @return array
     */
    public function getSelectedBusinessHours($id)
    {
        return $this->userBusinessHourRepo->getSelectedBusinessHours($id);
    }

    /**
     * @return array
     */
    public function getDiversityStatusInformation()
    {
        $types = explode("\n", Setting::getSetting('diversity_types'));
        $list = [];

        if (!empty($types)) {
            foreach ($types as $item) {
                list($key, $value) = explode('=', $item);
                $list[$key] = $value;
            }

            if (!empty($list)) {
                return $list;
            }
        }

        return [
            'mbe' => 'Minority Owned Business Enterprise',
            'wbe' => 'Woman Owned Business Enterprise',
            'glbt' => 'Gay, Lesbian, Bisexual, Transgender',
            'vbe' => 'Veteran Owned Business Enterprise',
            'other' => 'Other',
        ];
    }

    /**
     * @return array
     */
    public function getDiversityAgencyType()
    {
        return [
            'self' => 'Self Certified',
            'agency' => 'Agency Certified',
        ];
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersAcceptedByUserId($userId)
    {
        return $this->orderRepo->getOrdersAcceptedByUserId($userId);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getApprOrdersCompletedByUserId($userId)
    {
        return $this->orderRepo->getApprOrdersCompletedByUserId($userId);
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrdersAcceptedByUserIdWithLimit($userId, $limit = 10)
    {
        return $this->orderRepo->getOrdersAcceptedByUserIdWithLimit($userId, $limit);
    }

    public function getOrderAdjustedTurnTime(Order $order, $returnOriginal = false)
    {
        $dateDeliveredUnix = $this->getOrderDateDeliveredTimeStamp($order);

        $turnTime = $this->getOrderTurnTimeByDateDelivered($order, $dateDeliveredUnix);
        $turnTime = $this->getOrderTurnTimeInMinutesByTurnTimeString($turnTime);

        $delayedTimes = $this->getOrderDelayedDates($order->id);

        if ($delayedTimes) {
            foreach ($delayedTimes as $r) {
                $turnTime -= $r['diff'];
            }
        }

        if (!$returnOriginal && !count($delayedTimes)) {
            return 0;
        }

        return $turnTime;
    }

    /**
     * @param Order $order
     * @param $deliveredDate
     * @return string
     */
    public function getOrderTurnTimeByDateDelivered(Order $order, $deliveredDate)
    {
        if ($deliveredDate) {
            // Fallback:
            //return dateDiffHours($order->ordereddate, date('Y-m-d H:i', $deliveredDate));
            $diff = $this->getTotalNumberOfDays(strtotime($order->ordereddate), $deliveredDate);
            return sprintf('0 Mo %s D %s H %s M', $diff['d'], $diff['h'], $diff['m']);
        }
        return '';
    }

    public function getTotalNumberOfDays($from, $to)
    {
        $diff = abs($from - $to);
        return [
            'd' => intval($diff / 86400),
            'h' => intval(($diff % 86400) / 3600),
            'm' => intval(($diff / 60) % 60),
            's' => intval($diff % 60)
        ];
    }

    /**
     * @param $orderId
     * @return array
     */
    public function getOrderDelayedDates($orderId)
    {
        $items = [];
        $rows = $this->orderDelayDatesRepo->getOrderDelayDates($orderId);
        $exists = [];

        if ($rows) {
            foreach ($rows as $row) {
                $diff = dateDiffHours(date('Y-m-d H:i', $row->start_date), date('Y-m-d H:i', $row->end_date));
                $items[] = array(
                    'start' => $row->start_date,
                    'end' => $row->end_date,
                    'start_human' => date('m/d/Y H:i:s', $row->start_date),
                    'end_human' => date('m/d/Y H:i:s', $row->end_date),
                    'diff_human' => $diff,
                    'diff' => $this->getOrderTurnTimeInMinutesByTurnTimeString($diff),
                    'note' => null,
                );
            }
        }

        $rows = $this->orderDelayCodeRepo->getDelayCodes($orderId);
        if ($rows) {
            foreach ($rows as $row) {
                $diff = dateDiffHours(date('Y-m-d H:i', $row->start_date), date('Y-m-d H:i', $row->end_date));
                $items[] = array(
                    'start' => $row->start_date,
                    'end' => $row->end_date,
                    'start_human' => date('m/d/Y H:i:s', $row->start_date),
                    'end_human' => date('m/d/Y H:i:s', $row->end_date),
                    'diff_human' => $diff,
                    'diff' => $this->getOrderTurnTimeInMinutesByTurnTimeString($diff),
                    'note' => $row->note,
                );
            }
        }

        return $items;
    }

    public function getOrderTurnTimeInMinutesByTurnTimeString($time)
    {
        if (!$time) {
            return 0;
        }

        preg_match('/(-?\d+) Mo (-?\d+) D (-?\d+) H (-?\d+) M/', $time, $matches);

        if (!count($matches) == 5) {
            return 0;
        }


        $months = $matches[1];
        $days = $matches[2];
        $hours = $matches[3];
        $minutes = $matches[4];

        $total = $days;

        if ($months) {
            $total += ($months * 31);
        }

        if ($hours) {
            $total += ($hours / 24);
        }

        if ($minutes) {
            $total += ($minutes / 24 / 60);
        }

        return number_format($total, 3);
    }

    /**
     * @param Order $order
     * @return false|int|string
     */
    public function getOrderDateDeliveredTimeStamp(Order $order)
    {
        if ($order && $order->date_delivered) {
            return strtotime($order->date_delivered);
        }

        $row = $this->orderLogRepo->getByOrderIdAndInfo($order->id, '%QC Approved%');

        if ($row) {
            return strtotime($row->dts);
        }

        return '';
    }

    /**
     * @param $userId
     * @return int|mixed
     */
    public function getALOrdersAcceptedByUserIdCount($userId)
    {
        $total = $this->altOrderRepo->countOrdersByAcceptedBy($userId);

        $total += $this->altSubOrderRepo->countOrdersByAcceptedBy($userId);

        return $total;
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function getUserAgentOrders($userId)
    {
        return $this->altOrderRepo->getUserAgentOrders($userId);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|Collection|static[]
     */
    public function getUserAgentSubOrders($userId)
    {
        return $this->altSubOrderRepo->getUserAgentSubOrders($userId);
    }

    /**
     * @param AltOrder $order
     * @return mixed|string
     */
    public function getALFullProductTypeTitle(AltOrder $order)
    {
        $inspection = $this->orderHasExteriorInspection($order->id);
        $title = $order->product;
        if ($inspection) {
            return $title . ' + Exterior Inspection';
        }
        return $title;
    }

    /**
     * @param $id
     * @return bool
     */
    public function orderHasExteriorInspection($id)
    {
        $row = $this->altOrderProductTypeRepo->getByCode('EXTERIOR_INSPECTION');
        if ($row) {
            $relation = $this->altOrderProductRelationRepo->getByOrderAndProduct($id, $row->id);
            return $relation ? true : false;
        }
        return false;
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserSubmittedOrderswithLimit($userId, $limit = 10)
    {
        return $this->orderRepo->getUserSubmittedOrderswithLimit($userId, $limit);
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getALUserSubmittedOrdersWithLimit($userId, $limit = 10)
    {
        return $this->altOrderRepo->getALUserSubmittedOrdersWithLimit($userId, $limit);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserNotes($userId)
    {
        return $this->userNotesRepo->getUserNotes($userId);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getApprOrdersCompletedByUserIdWithDeliveredDate($userId)
    {
        return $this->orderRepo->getApprOrdersCompletedByUserIdWithDeliveredDate($userId);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersAcceptedByUserIdWithDeliveredDate($userId)
    {
        return $this->orderRepo->getOrdersAcceptedByUserIdWithDeliveredDate($userId);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getALOrdersCompletedByUserId($userId)
    {
        $orders = [];
        $rows = $this->altOrderRepo->getCompletedOrdersByAcceptedUser($userId);
        if ($rows) {
            foreach ($rows as $r) {
                $orders[$r->id] = $r->id;
            }
        }
        $rows = $this->altSubOrderRepo->getCompletedSubOrdersByAcceptedUser($userId);
        if ($rows) {
            foreach ($rows as $r) {
                $orders[$r->parent_order_id] = $r->parent_order_id;
            }
        }

        return count($orders);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersPlacedByUserId($userId)
    {
        return $this->orderRepo->getOrdersPlacedByUserId($userId);
    }

    /**
     * @param $userId
     * @return int
     */
    public function getOrdersCompletedByUserId($userId)
    {
        return $this->orderRepo->getOrdersCompletedByUserId($userId);
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppraiserOrderTypeBreakdown($userId)
    {
        return $this->orderRepo->getAppraiserOrderTypeBreakdown($userId);
    }

    /**
     * @param $userId
     * @param int $days
     * @return int
     */
    public function getAppraiserOrdersTurnOut($userId, $days = 7)
    {
        return $this->orderRepo->getAppraiserOrdersTurnOut($userId, $days);
    }

    /**
     * @param $userId
     * @param null $dateFrom
     * @param null $dateTo
     * @return array
     */
    public function getAppraiserOrdersWithQCCorrections($userId, $dateFrom = null, $dateTo = null)
    {
        $rows = $this->orderRepo->getByAcceptedUserAndDeliveredDate($userId, $dateFrom, $dateTo);
        $list = [];
        $totalCorrections = 0;
        if ($rows) {
            foreach ($rows as $row) {
                // Get qc corrections for each file
                $total = 0;

                $qc = $this->apprQcRepo->getNoSentByOrderId($row->id);
                if ($qc) {
                    foreach ($qc as $q) {
                        // Get wrong corrections
                        $wrong = $this->apprQcAnswerRepo->getWrongCorrections($q->id);
                        if ($wrong) {
                            foreach ($wrong as $w) {
                                $total += $w->total;
                            }
                        }
                    }
                }
                $customCorrections = $this->apprQcOrderCorrectionRepo->getCustomCorrections($row->id);

                $total += $customCorrections->total;

                $totalCorrections += $total;

                if ($total) {
                    $list['count'][$row->id] = $total;
                } else {
                    $list['all'][$row->id] = $total;
                }
            }
        }
        $list['total'] = $totalCorrections;
        return $list;
    }

    /**
     * @param $userId
     * @param null $dateFrom
     * @param null $dateTo
     * @return array
     */
    public function getAppraiserOrdersWithUWConditions($userId, $dateFrom = null, $dateTo = null)
    {

        $rows = $this->orderRepo->getByAcceptedUserAndDeliveredDate($userId, $dateFrom, $dateTo);
        $list = [];
        $totalConditions = 0;

        if ($rows) {
            foreach ($rows as $row) {
                // Get qc corrections for each file
                $total = 0;

                $uw = $this->apprUwRepo->getByOrder($row->id);
                if ($uw) {
                    foreach ($uw as $q) {
                        // Get wrong corrections
                        $wrong = $this->apprUwAnswersRepo->getWrongCorrections($q->id);
                        if ($wrong) {
                            foreach ($wrong as $w) {
                                $total += $w->total;
                            }
                        }
                    }
                }

                $totalConditions += $total;

                if ($total) {
                    $list['count'][$row->id] = $total;
                } else {
                    $list['all'][$row->id] = $total;
                }
            }
        }
        $list['total'] = $totalConditions;

        return $list;
    }

    /**
     * @param $apprId
     * @param bool $returnFullAdjusted
     * @param null $dateFrom
     * @param null $dateTo
     * @param bool $debug
     * @return array|mixed
     */
    public function getAppraiserAverageTurnTime($apprId, $returnFullAdjusted = false, $dateFrom = null, $dateTo = null, $debug = false)
    {
        if ($dateFrom && $dateTo) {
            $totalCompleted = $this->orderRepo->getAppraiserTotalCompletedFilesByDateRange($apprId, $dateFrom, $dateTo);
        } else {
            $totalCompleted = $this->orderRepo->getAppraiserTotalCompletedFullFiles($apprId);
        }

        $items = [
            'submit' => 0,
            'qc' => 0,
            'uw' => 0,
            'inspection' => 0,
            'total' => 0,
            'adjusted' => 0,
            'placed' => 0,
            'accepted' => 0,
            'accepted_to_scheduled' => 0,
            'scheduled_to_delivered' => 0
        ];

        // Early exit
        if (!$totalCompleted) {
            return $items;
        }

        // Get from cache
        // Cache days to save
        $daysToSave = 60 * 60 * 24 * 1;

        // Get from cache
        $cacheKey = sprintf('appruser_averageturntimes_%s_%s_%s', $apprId, $dateFrom, $dateTo);
        $basicInfo = \Cache::get($cacheKey);

        if ($basicInfo) {
            return $basicInfo;
        }

        // Get all completed files
        $times = [
            'submit' => 0,
            'qc' => 0,
            'uw' => 0,
            'inspection' => 0,
            'total' => 0,
            'adjusted' => 0,
            'sentBackToDelivered' => 0,
            'sentBackSecondToDelivered' => 0,
            'placed' => 0,
            'placedToDelivered' => 0,
            'inspectionToDelivered' => 0,
            'accepted' => 0,
            'accepted_to_scheduled' => 0,
            'scheduled_to_delivered' => 0
        ];
        if ($dateFrom && $dateTo) {
            $orders = $this->orderRepo->getByAcceptedUserDeliveredRangeAndStatuses([Order::STATUS_APPRAISAL_COMPLETED], $dateFrom, $dateTo, $apprId);
        } else {
            $orders = $this->orderRepo->getAppraiserTotalCompleted($apprId);
        }
        if ($orders && count($orders)) {
            foreach ($orders as $order) {
                // Get time difference between assigned and submitted
                $totalTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->date_delivered));
                $assignedTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->submitted));

                $accepetedToScheduled = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->schd_date));
                $scheduledToDelivered = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->schd_date, $order->date_delivered));

                $qcTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->submitted, $order->date_delivered));
                $inspectionTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->schd_date, $order->submitted));

                $placedToDelivered = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->ordereddate, $order->date_delivered));
                $inspectionToDelivered = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->schd_date, $order->date_delivered));

                if ($order->date_uw_received && $order->date_uw_completed) {
                    $uwTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->date_uw_received, $order->date_uw_completed));
                }

                $placed = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->ordereddate, $order->date_delivered));
                $accepted = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->date_delivered));

                $adjusted = $this->getOrderAdjustedTurnTime($order->id, $returnFullAdjusted);

                if ($adjusted) {
                    $totalTime = $adjusted;
                }

                // Pull time from first time sent back to delivered
                list($sentBack, $sentBackSecond) = $this->qcStatsRepo->getSentBack($order->id);

                $sentBackToDelivered = 0;
                $sentBackSecondToDelivered = 0;


                if ($sentBack) {
                    $sentBackToDelivered = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates(date('Y-m-d H:i:s', $sentBack->created_date), $order->date_delivered));
                }

                if ($sentBackSecond) {
                    $sentBackSecondToDelivered = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates(date('Y-m-d H:i:s', $sentBackSecond->created_date), $order->date_delivered));
                }

                if ($debug) {
                    print_r($sentBack);
                    print_r($sentBackSecond);

                    print_r($sentBackToDelivered);
                    print_r($sentBackSecondToDelivered);
                }

                $times['submit'] += $assignedTime;
                $times['qc'] += $qcTime;
                $times['uw'] += $uwTime;
                $times['inspection'] += $inspectionTime;
                $times['placedToDelivered'] += $placedToDelivered;
                $times['inspectionToDelivered'] += $inspectionToDelivered;

                $times['sentBackToDelivered'] += $sentBackToDelivered;
                $times['sentBackSecondToDelivered'] += $sentBackSecondToDelivered;

                $times['total'] += $totalTime;
                $times['adjusted'] += $adjusted;

                $times['accepted_to_scheduled'] += $accepetedToScheduled;
                $times['scheduled_to_delivered'] += $scheduledToDelivered;

                $times['placed'] += $placed;
                $times['accepted'] += $accepted;
            }

            // Average
            $items['submit'] = ($times['submit'] / $totalCompleted);
            $items['qc'] = ($times['qc'] / $totalCompleted);
            $items['uw'] = ($times['uw'] / $totalCompleted);
            $items['inspection'] = ($times['inspection'] / $totalCompleted);
            $items['placedToDelivered'] = ($times['placedToDelivered'] / $totalCompleted);
            $items['inspectionToDelivered'] = ($times['inspectionToDelivered'] / $totalCompleted);

            $items['sentBackToDelivered'] = ($times['sentBackToDelivered'] / $totalCompleted);
            $items['sentBackSecondToDelivered'] = ($times['sentBackSecondToDelivered'] / $totalCompleted);

            $items['total'] = ($times['total'] / $totalCompleted);
            $items['adjusted'] = ($times['adjusted'] / $totalCompleted);

            $items['accepted_to_scheduled'] = ($times['accepted_to_scheduled'] / $totalCompleted);
            $items['scheduled_to_delivered'] = ($times['scheduled_to_delivered'] / $totalCompleted);

            $items['placed'] = ($times['placed'] / $totalCompleted);
            $items['accepted'] = ($times['accepted'] / $totalCompleted);
            $items['totalCompleted'] = $totalCompleted;
        }

        \Cache::set($cacheKey, $items, $daysToSave);

        return $items;
    }

    /**
     * @param $apprId
     * @param int $days
     * @param bool $returnFullAdjusted
     * @return array|mixed
     */
    public function getAppraiserAverageTurnTimeDaysRange($apprId, $days = 90, $returnFullAdjusted = false)
    {

        $totalCompleted = $this->userGeneratorRepo->getAppraiserTotalCompletedFullFilesDaysRange($apprId, $days);
        $items = [
            'submit' => 0,
            'qc' => 0,
            'uw' => 0,
            'inspection' => 0,
            'total' => 0,
            'adjusted' => 0,
            'placed' => 0,
            'accepted' => 0,
            'accepted_to_scheduled' => 0,
            'scheduled_to_delivered' => 0
        ];

        $time = strtotime(sprintf("-%s days", $days));

        // Early exit
        if (!$totalCompleted) {
            return $items;
        }

        // Get from cache
        // Cache days to save
        $daysToSave = 60 * 60 * 24 * 1;

        // Get from cache
        $cacheKey = sprintf('appruser_averageturntime_%s_%s', $apprId, $days);
        $basicInfo = \Cache::get($cacheKey);

        if ($basicInfo) {
            return $basicInfo;
        }

        // Get all completed files
        $times = [
            'submit' => 0,
            'qc' => 0,
            'uw' => 0,
            'inspection' => 0,
            'total' => 0,
            'adjusted' => 0,
            'placed' => 0,
            'accepted' => 0,
            'accepted_to_scheduled' => 0,
            'scheduled_to_delivered' => 0
        ];
        $orders = $this->orderRepo->completedOrders($apprId, $time);
        if ($orders) {
            foreach ($orders as $order) {
                // Get time difference between assigned and submitted
                $totalTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->date_delivered));
                $assignedTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->submitted));

                $accepetedToScheduled = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->schd_date));
                $scheduledToDelivered = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->schd_date, $order->date_delivered));

                $qcTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->submitted, $order->date_delivered));
                $inspectionTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->schd_date, $order->submitted));

                if ($order->date_uw_received && $order->date_uw_completed) {
                    $uwTime = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->date_uw_received, $order->date_uw_completed));
                }

                $placed = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->ordereddate, $order->date_delivered));
                $accepted = $this->getOrderTurnTimeInMinutesByTurnTimeString(getOrderTurnTimeByDates($order->accepteddate, $order->date_delivered));

                $adjusted = $this->getOrderAdjustedTurnTime($order->id, $returnFullAdjusted);

                if ($adjusted) {
                    $totalTime = $adjusted;
                }

                $times['submit'] += $assignedTime;
                $times['qc'] += $qcTime;
                $times['uw'] += $uwTime;
                $times['inspection'] += $inspectionTime;
                $times['total'] += $totalTime;
                $times['adjusted'] += $adjusted;

                $times['accepted_to_scheduled'] += $accepetedToScheduled;
                $times['scheduled_to_delivered'] += $scheduledToDelivered;

                $times['placed'] += $placed;
                $times['accepted'] += $accepted;
            }

            // Average
            $items['submit'] = ($times['submit'] / $totalCompleted);
            $items['qc'] = ($times['qc'] / $totalCompleted);
            $items['uw'] = ($times['uw'] / $totalCompleted);
            $items['inspection'] = ($times['inspection'] / $totalCompleted);
            $items['total'] = ($times['total'] / $totalCompleted);
            $items['adjusted'] = ($times['adjusted'] / $totalCompleted);

            $items['accepted_to_scheduled'] = ($times['accepted_to_scheduled'] / $totalCompleted);
            $items['scheduled_to_delivered'] = ($times['scheduled_to_delivered'] / $totalCompleted);

            $items['placed'] = ($times['placed'] / $totalCompleted);
            $items['accepted'] = ($times['accepted'] / $totalCompleted);

            $items['totalCompleted'] = $totalCompleted;
        }

        \Cache::set($cacheKey, $items, $daysToSave);

        return $items;
    }

    /**
     * @param $state
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getStateComplianceRecordByState($state)
    {
        return $this->stateComplianceRepo->getStateComplianceRecordByState($state);
    }

    public function getAppraiserPreferredGroups($userId)
    {
        return $this->preferApprRepo->getAppraiserPreferredGroups($userId);
    }

    public function getEmailTemplatesByCategory($userId)
    {
        $templates = $this->emailTemplatesRepo->getEmailTemplates();
        $list = [];

        // Load personal email templates
        $personal = $this->getTemplatesList($userId, true);
        if ($personal) {
            foreach ($personal as $templateId => $templateTitle) {
                $list['My Templates'][$templateId] = $templateTitle;
            }
        }

        foreach ($templates as $item) {
            $list[$this->getEmailTemplateaCategory($item->category)][$item->id] = $item->title;
        }

        return $list;
    }

    /**
     * Get categories dropdown list
     */
    public function getTemplatesList($userId, $approved = false)
    {
        $list = [];
        $rows = $this->userTemplatesRepo->getTemplates($userId, $approved);
        foreach ($rows as $row) {
            $list['u_' . $row->id] = $row->title;
        }
        return $list;
    }

    /**
     * @param $c
     * @return mixed|string
     */
    protected function getEmailTemplateaCategory($c)
    {
        $rows = $this->getEmailCategories();
        return isset($rows[$c]) ? $rows[$c] : 'N/A';
    }

    /**
     * @return array
     */
    protected function getEmailCategories()
    {
        return [
            'client' => 'Clients',
            'appr' => 'Appraisers',
            'sales' => 'Sales',
        ];
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserLastFirstDataProfile($id)
    {
        return $this->userFdProfileRepo->getUserLastFirstDataProfile($id);
    }

    /**
     * @param $id
     * @return array
     */
    public function getActivityRecords($id)
    {
        $records = $this->userActivityRecordRepo->getAllByUserId($id);
        $list = [];
        foreach ($records as $record) {
            // Replace record column name with a value
            $record->title = $record->column_name;

            $list[$record->id]['date'] = $record->created_date;
            $list[$record->id]['username'] = $record->fullname;
            $list[$record->id]['authorname'] = $record->author_fullname;
            $list[$record->id]['rows'][] = $record;
        }

        return $list;
    }

    /**
     * @return Collection
     */
    public function getAmcList()
    {
        return $this->amcRepo->getForDropdown();
    }

    /**
     * @return Collection
     */
    public function getAPIAccounts()
    {
        return $this->apiUserRepo->getAPIAccounts();
    }

    public function getAdminUserTypes()
    {
        return [
            'L' => 'Limited',
            'O' => 'Sales',
            'R' => 'Regular',
            'T' => 'Team Leader',
            'S' => 'Super User',
        ];
    }

    public function getAdminGroupsDropdown()
    {
        return $this->adminGroupRepo->getAdminGroupsDropdown();
    }

    public function getAdminGroupFormattedTitle($id)
    {
        $row = $this->adminGroupRepo->getAdminGroupById($id);
        if ($row) {
            $color = '';
            $style = '';
            if ($row->color) {
                $color = "color:" . $row->color . ";";
            }
            if ($row->style) {
                $style = $row->style;
            }
            return "<span style='" . $color . $style . "'>" . $row->title . "</span>";
        }
        return '';
    }

    public function getAdminGroupPermissions()
    {
        $cacheKey = sprintf('admin_group_permissions');
        $value = \Cache::get($cacheKey);

        if ($value) {
            return $value;
        }

        $rows = $this->tigerService->getAdminGroupPermissions();

        // Store in cache
        \Cache::set($cacheKey, $rows, 60 * 60 * 24);

        return $rows;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getActivePriorityInviteByUserId($id)
    {
        return $this->apprPriorityInvitesRepo->getActivePriorityInviteByUserId($id);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteAllLanguagesByUser($userId)
    {
        return $this->userLanguageRepo->deleteAllByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertLanguagesByArray($data = [])
    {
        return $this->userLanguageRepo->insertByArray($data);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteUserApprTypes($userId)
    {
        return $this->userApprTypeRepo->deleteByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertApprTypesByArray($data = [])
    {
        return $this->userApprTypeRepo->insertByArray($data);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteUserLoanTypes($userId)
    {
        return $this->userLoanTypeRepo->deleteAllByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertLoanTypesByArray($data = [])
    {
        return $this->userLoanTypeRepo->insertByArray($data);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteUserLoanPurposes($userId)
    {
        return $this->userLoanPurposeRepo->deleteAllByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertLoanPurposesByArray($data = [])
    {
        return $this->userLoanPurposeRepo->insertByArray($data);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteApprReferences($userId)
    {
        return $this->apprReferenceRepo->deleteByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertApprReferencesByArray($data = [])
    {
        return $this->apprReferenceRepo->insertByArray($data);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteUserAppraiserSplit($userId)
    {
        return $this->userDataAppraiserSplitRepo->deleteByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertApprSplitByArray($data = [])
    {
        return $this->userDataAppraiserSplitRepo->insertByArray($data);
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteApprEmailRules($userId)
    {
        return $this->apprEmailRulesRepo->deleteByUser($userId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertEmailRulesByArray($data = [])
    {
        return $this->apprEmailRulesRepo->insertByArray($data);
    }

    /**
     * @param $id
     * @param $userId
     */
    public function findCert($id, $userId)
    {
        return $this->certRepo->findByIdAndUser($id, $userId);
    }

    public function convertAppraisalOrderGroups(User $user, $oldGroupId, $newGroupId)
    {
        if (!$user) {
            return false;
        }

        // We convert only for clients
        if ($user->user_type != User::USER_TYPE_CLIENT) {
            return false;
        }

        // Convert if groups are different
        if ($oldGroupId == $newGroupId) {
            return false;
        }

        $oldGroupRow = $this->userGroupsRepo->getGroupData($oldGroupId);
        $newGroupRow = $this->userGroupsRepo->getGroupData($newGroupId);

        if ($newGroupRow) {
            $this->orderRepo->updateByOrderedBy($user->id, ['groupid' => $newGroupId]);
            // Add user note
            $this->userNotesRepo->insert([
                'userid' => $user->id,
                'adminid' => getUserId(),
                'notes' => sprintf("Updated users orders group relation from <b>%s</b> to <b>%s</b>", $oldGroupRow->descrip, $newGroupRow->descrip),
                'dts' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        } else {
            // Add user note
            $this->userNotesRepo->insert([
                'userid' => $user->id,
                'adminid' => getUserId(),
                'notes' => 'Could not update users orders since user group was not found',
                'dts' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        return true;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getGroupInfoById($id)
    {
        return $this->userGroupsRepo->getGroupData($id);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateClientsById($id, $data = [])
    {
        return $this->userGroupsRepo->updateById($id, $data);
    }

    /**
     * @param $userId
     * @param $items
     * @return bool
     */
    public function saveSuperVisingUser($userId, $items)
    {
        return $this->supervisingUserRepo->save($userId, $items);
    }

    public function recordVendorTaxChange(User $user, $ein, $taxClass, $company)
    {
        if (!$user) {
            return false;
        }

        if (!$ein && !$taxClass) {
            return false;
        }

        $this->vendorRepo->insert([
            'user_id' => $user->id,
            'ein' => $ein,
            'tax_class' => $taxClass,
            'company' => $company,
            'created_date' => Carbon::now()->timestamp,
            'created_by' => getUserId()
        ]);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateClientsBySalesId($id, $data = [])
    {
        return $this->userGroupsRepo->updateBySalesId($id, $data);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateClientsBySalesId2($id, $data = [])
    {
        return $this->userGroupsRepo->updateBySalesId2($id, $data);
    }

    /**
     * @param User $user
     */
    public function updateAppraiserFHAStateApproved(User $user)
    {
        // Delete current records
        $this->fhaStateApprovedRepo->deleteByUserId($user->id);

        $rows = $this->getUserOldHUDLicenses($user);
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'user_id' => $user->id,
                'state' => $row->state,
                'expiration' => $row->license_exp_unix,
                'expiration_human' => $row->license_exp_human,
                'license_number' => trim($row->license_number),
            ];
        }
        $this->fhaStateApprovedRepo->insert($data);
    }

    /**
     * @param $id
     * @param $methods
     * @return bool
     */
    public function saveCommunicationMethods($id, $methods)
    {
        $this->userCommunicationPreferenceRepo->deleteByUserId($id);
        $data = [];
        if ($methods) {
            foreach ($methods as $method) {
                $data[] = [
                    'user_id' => $id,
                    'type' => $method
                ];
            }
            $this->userCommunicationPreferenceRepo->insert($data);
        }
        return true;
    }

    /**
     * @param $id
     * @param $hours
     * @return bool
     */
    public function saveBusinessHours($id, $hours)
    {
        $this->userBusinessHourRepo->deleteByUser($id);
        if ($hours) {
            $data = [];
            foreach ($hours as $day => $hour) {
                if ($hour['from'] || $hour['to']) {
                    $data[] = [
                        'user_id' => $id,
                        'day' => $day,
                        'hour_from' => $hour['from'],
                        'hour_to' => $hour['to']
                    ];
                }
            }
            $this->userBusinessHourRepo->insert($data);
        }
        return true;
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function removePermissions($userId)
    {
        return $this->userClientPermissionRepo->deleteByUser($userId);
    }

    /**
     * @param $userId
     * @param $permissions
     * @return bool
     */
    public function savePermissions($userId, $permissions)
    {
        return $this->userClientPermissionRepo->savePermissions($userId, $permissions);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertUserNotes($data = [])
    {
        return $this->userNotesRepo->insert($data);
    }

    /**
     * @param $userId
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserOrderLogsWithLimit($userId, $limit = 10)
    {
        return $this->orderLogRepo->getUserOrderLogsWithLimit($userId, $limit);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getEmailTemplate($id)
    {
        return $this->emailTemplatesRepo->getOne($id);
    }

    public function convertOrderKeysToValues($temp, $order, $full = true)
    {
        if ($order && count($order)) {
            $temp = $this->convertKeys($temp, $order, $full);
        }

        if ((getUserId() && isAdminUser())) {
            $temp = str_replace('{signature}', getUser()->email_signature, $temp);
        }

        // Make sure we do not replace {html}
        $temp = str_replace('{html}', '%html%', $temp);
        $temp = str_replace('{corrections}', '%corrections%', $temp);

        // Remove any {(.*)} left
        $temp = preg_replace('/\{(.*)\}/', '', $temp);

        // Bring back {html}
        $temp = str_replace('%html%', '{html}', $temp);
        $temp = str_replace('%corrections%', '{corrections}', $temp);

        return $temp;
    }

    public function convertKeys($message, $order, $full = false)
    {
        $orderKeys = [];

        // We load everything
        $items = [
            '{apprtype}' => $order->apprTypeName,
            '{loantype}' => $order->loanTypeTitle,
            '{loanreason}' => $order->loanReasonName,
            '{due_date}' => $order->due_date ? date('m/d/Y', $order->due_date) : '',
            '{client_due_date}' => $order->client_due_date ? date('m/d/Y', $order->client_due_date) : '',
            '{FULL_URL}' => env('APP_URL'),
            '{FULLURL}' => env('APP_URL'),
            '{URL}' => env('APP_URL'),
            '{date}' => date('m/d/Y'),
            '{datetime}' => date('m/d/Y g:i A'),
            '{apiname}' => $order->apiName,
            '{paymentstatus}' => $order->paymentStatus,
            '{status.name}' => $order->statusName,
            '{addendas}' => $order->addendasList,
            '{phone}' => $order->teamPhone,
            '{fulladdress}' => $order->address,
            '{date_delivered}' => date('m/d/Y H:i', strtotime($order->date_delivered)),
            '{fha_case_effective_date}' => $order->fha_case_effective_date ? date('m/d/Y', strtotime($order->fha_case_effective_date)) : '',
            '{real_lender}' => $order->real_lender ?: $order->lender,
            '{real_lender_address}' => $order->real_lender_address ?: '',
            '{real_lender_city}' => $order->real_lender_city ?: '',
            '{real_lender_state}' => $order->real_lender_state ?: '',
            '{real_lender_zip}' => $order->real_lender_zip ?: '',
            '{scheduled_appointments}' => $order->appointmentScheduleMessage
        ];

        if ((getUserId() && isAdminUser())) {
            $items['{signature}'] = getUser()->email_signature;
        }

        foreach ($order as $k => $v) {
            if (!isset($items['{' . $k . '}'])) {
                $items['{' . $k . '}'] = $v;
            }
        }

        // Convert user info
        $user = $this->userRepo->getUserInfoById($order->orderedby);
        if ($user && count($user)) {
            foreach ($user as $k => $v) {
                $items['{user.' . $k . '}'] = $v;
            }
        }

        // Convert group info
        $group = $order->groupData;
        if ($group) {
            foreach ($group as $k => $v) {
                $items['{group.' . $k . '}'] = $v;
            }
        }

        // Convert lender info
        $lender = $order->lenderRecord;
        if ($lender) {
            foreach ($lender as $k => $v) {
                $items['{lender.' . $k . '}'] = $v;
            }
        }

        // Convert amc info
        $amc = $order->amc;
        if ($amc) {
            foreach ($amc as $k => $v) {
                $items['{amc.' . $k . '}'] = $v;
            }
        }

        // Convert settings
        $settings = Setting::getSettings();
        if ($settings && count($settings)) {
            foreach ($settings as $k => $v) {
                $items['{setting.' . $k . '}'] = $v;
            }
        }


        $orderKeys[$order->id] = $items;

        // We have everything loaded here
        return strtr($message, $orderKeys[$order->id]);
    }

    public function addCard(User $user, $data = [])
    {
        $userFdProfile = new UserFdProfile();
        $userFdProfile->user_id = $user->id;
        $userFdProfile->card_name = $data['name'];
        $userFdProfile->zipcode = $data['zip'] ?? 0;
        $userFdProfile->created_date = Carbon::now()->timestamp;
        $userFdProfile->card_exp = $data['exp'] ?? 0;
        $userFdProfile->created_by = getUserId();
        $userFdProfile->credit_type = StringHelper::creditCardCompany($data['number']);
        $userFdProfile->credit_number = StringHelper::maskCreditCard(StringHelper::formatCreditCard($data['number']));
        $userFdProfile->cvv = $data['cvv'] ?? 0;
        $userFdProfile->card_address = ucwords(strtolower($data['address']));
        $userFdProfile->card_city = ucwords(strtolower($data['city']));
        $userFdProfile->card_state = strtoupper($data['state']);
        $userFdProfile->card_zip = $data['zip'];
        $userFdProfile->save();
        return $userFdProfile;
    }

    public function resetPasswordEmail(User $user)
    {
        $passwordResetKey = sha1(microtime());
        $link = route('admin.reset_password') . '?key=' . $passwordResetKey;
        //$link = HTTPS_BASE_URL . '/reset.php?k=' . $passwordResetKey;

        $emailMessageContent = Setting::getSetting('password_reset_email');
        $emailMessageContent = str_replace(array('{name}', '{link}'), array(getUserFullNameById($user->id), $link), $emailMessageContent);
        $emailMessageContent = $this->convertSettingsContent($emailMessageContent);
        dispatch(new SendResetPasswordEmail('Password Reset Request', $emailMessageContent, $user->email));
        // Send the message
        try {
            $user->password_reset_key = $passwordResetKey;
            $user->save();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    protected function convertSettingsContent($content)
    {
        $settings = Setting::getSettings();
        if ($settings && count($settings)) {
            foreach ($settings as $k => $v) {
                $content = str_replace('{setting.' . $k . '}', $v, $content);
            }
        }

        return $content;
    }

    /**
     * @param $path
     * @return bool
     */
    public function createS3Folder($path)
    {
        $storage = $this->storage->make();
        return $storage->makeDirectory($path);
    }

    /**
     * @param $path
     * @param $file
     * @param $name
     * @return bool
     */
    public function putFileToS3($path, $file, $name)
    {
        $storage = $this->storage->make();
        return $storage->putFileAs($path, $file, $name);
    }

    /**
     * @param $key
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getTeamByKey($key)
    {
        return $this->adminTeamsRepo->getTeamByKey($key);
    }

    /**
     * @param $type
     * @return string
     */
    public function getUserDocumentTypeName($type)
    {
        $type = str_replace(['_'], [' '], $type);
        return ucwords(strtolower($type));
    }

    public function downloadFileFromS3($path)
    {
        $storage = $this->storage->make();
        return $storage->download($path);
    }

    /**
     * @param $path
     * @return string
     */
    public function getFileFromS3($path)
    {
        $storage = $this->storage->make();
        return $storage->download($path, null, [
            'Content-Disposition' => 'inline'
        ]);
    }

    public function uploadUserDocument(User $user, UploadedFile $file, $type, $kind = 'background')
    {
        $size = $file->getSize();

        $userDocumentsDir = public_path('/user_documents/');
        $userDir = $userDocumentsDir . $user->id . '/';
        if(!is_dir($userDir)) {
            mkdir($userDir, 0777, true);
        }

        // Create AWS S3 Folder
        try {
            $this->createS3Folder('user_documents/' . $user->id);
        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        try {
            // Copy file to new location
            $newName = StringHelper::getCustomShortHash() . '_' . $file->getFilename() . '.' . $file->getClientOriginalExtension();
            try {
                $this->putFileToS3('/user_documents/' . $user->id, $file, $newName);
            } catch(\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }

            switch ($kind) {
                case 'eando': case 'background':
                    UserDoc::where('type', $type)
                        ->where('userid', $user->id)->delete();
                    $userDoc = new UserDoc();
                    $userDoc->userid = $user->id;
                    $userDoc->type = $type;
                    $userDoc->created_date = Carbon::now()->timestamp;
                    $userDoc->created_by =getUserId();
                    $userDoc->name = $file->getClientOriginalName();
                    $userDoc->filesize = $size;
                    $userDoc->filetype = $file->getClientOriginalExtension();
                    $userDoc->filename = $newName;
                    $userDoc->is_aws = 1;
                    $userDoc->save();
                    break;
                case 'additional':
                    $userDoc = new UserDocument();
                    $userDoc->userid = $user->id;
                    $userDoc->type = $type;
                    $userDoc->created_date = Carbon::now()->timestamp;
                    $userDoc->created_by =getUserId();
                    $userDoc->name = $file->getClientOriginalName();
                    $userDoc->filesize = $size;
                    $userDoc->filetype = $file->getClientOriginalExtension();
                    $userDoc->filename = $newName;
                    $userDoc->save();
                    break;
            }

        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}