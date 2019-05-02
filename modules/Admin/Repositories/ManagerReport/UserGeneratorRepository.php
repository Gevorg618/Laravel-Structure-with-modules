<?php

namespace Modules\Admin\Repositories\ManagerReport;

use App\Models\Customizations\Status;
use App\Models\Tools\Setting;

class UserGeneratorRepository
{   
    
    /**
     * Create a new instance of CustomPagesManagerRepository class.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    /**
     * get status
     *
     * @return void
     */
    public function getStatuses()
    {
       return Status::all()->pluck('descrip', 'id');
    }

    /**
     * get status
     *
     * @return void
     */
    public function generateDataForDownload($data)
    {

        $dateRange = explode("-", $data['daterange']);

        
        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));
        $dateType = $data['datetype'];
        $client =   isset($data['client']) ? $data['client'] : "";
        $groupExclude = isset($data['group_exclude']) ? $data['group_exclude'] : "";
        $newConstExpert = isset($data['new_construction_expert']) ? $data['new_construction_expert'] : "";
        $diversityStatus = isset($data['diversity_status']) ? $data['diversity_status'] : "";

        // Order Specific
        $status = $data['status'];

        // User Specific
        $name = trim($data['name']);
        $userType = $data['user_type'];
        $state = $data['user_state'];
        $county = isset($data['county']) ? $data['county'] : "";
        $company = $data['company'];
        $address = $data['address'];
        $city = $data['city'];
        $isPriorityAppr = $data['is_priority_appr'];
        $exclude = $data['exclude'];
        $active = $data['active'];

        $minCount = isset($data['min_count']) ?  $data['min_count'] : $data['min_count'] = 1;

        // // Columns
        $columns = isset($data['columns']) ? $data['columns'] : [];
        $select = ['u.*, o.*'];
        $joins = [];
        $conditions = [];
        $where = '';

        
        $joins[] = "LEFT JOIN user_data o ON (u.id=o.user_id)";

        if (in_array('fha_license', $columns) || in_array('fha_license_expiration', $columns)) {
            $select[] = 'fha.license_number, fha.license_exp_human as fha_license_expiration';
            $joins[] = "LEFT JOIN appr_fha_license fha ON ( LOWER(SUBSTRING(fha.zip, 1, 5)) = LOWER(SUBSTRING(o.comp_zip, 1, 5)) AND LOWER(fha.firstname) = LOWER(o.firstname) AND LOWER(fha.lastname) = LOWER(o.lastname))";
        }

        if (in_array('asc_license', $columns) || in_array('asc_license_type', $columns) || in_array('asc_license_expiration', $columns)) {
            $select[] = 'ascdata.lic_number as asc_license_number, ascdata.lic_type as asc_license_type, ascdata.exp_date as asc_license_expiration';
            $joins[] = "LEFT JOIN asc_data ascdata ON ( LOWER(SUBSTRING(ascdata.zip, 1, 5)) = LOWER(SUBSTRING(o.comp_zip, 1, 5)) AND LOWER(ascdata.fname) = LOWER(o.firstname) AND LOWER(ascdata.lname) = LOWER(o.lastname))";
        }

        if (in_array('state_license', $columns) || in_array('state_license_type', $columns) || in_array('state_license_state', $columns) || in_array('state_license_expiration', $columns)) {
            $select[] = 'cert.cert_num as state_license, cert.license_type as state_license_type, cert.state as state_license_state, cert.cert_expire as state_license_expiration';
            $joins[] = "LEFT JOIN appr_cert cert ON (cert.user_id=u.id)";
        }

        if(in_array('client_pricing_version', $columns) || in_array('date_company_joined', $columns)) {
            $joins[] = "LEFT JOIN user_groups cg ON (cg.id=u.groupid)";
            $joins[] = "LEFT JOIN appr_state_price_version pv ON (pv.id=cg.pricing_version)";
            $select[] = "pv.title as client_pricing_version, cg.created_date as date_company_joined";
        }

        // SQL Statement
        $sql = "SELECT " . implode(',', $select) . "
                FROM user u\n";

        // Add county postal codes join
        if ($county) {
            $joins[] = "LEFT JOIN zip_code c ON (o.comp_zip=c.zip_code OR o.zip=c.zip_code)";
        }

        // Group Exclude
        if ($groupExclude && $client) {
            // Excluded appraiser
            $joins[] = "LEFT JOIN user_exclude ue ON (ue.apprid=u.id)";
        }
        // Add client condition
        elseif (!$groupExclude && $client) {
            $joins[] = "LEFT JOIN user_groups g ON (g.id=u.groupid)";
        }

        // Add name condition
        if ($name) {
            $nameCondition = array();
            $nameCondition[] = ("LOWER(u.email) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($name) . '%') . "");

            // Other
            $nameCondition[] = ("LOWER(o.firstname) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($name) . '%') . "");
            $nameCondition[] = ("LOWER(o.lastname) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($name) . '%') . "");
            $nameCondition[] = ("LOWER(TRIM(CONCAT(o.firstname,' ',o.lastname))) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($name) . '%') . "");

            // Combine
            $conditions[] = '(' . implode(' OR ', $nameCondition) . ')';
        }
        
        // Is priority
        if ($isPriorityAppr != '' && $userType == 4) {
            $priorityApprCondition = array();
            if ($isPriorityAppr == 1) {
                $priorityApprCondition[] = ("o.is_priority_appr=1");
            } elseif ($isPriorityAppr == 0) {
                $priorityApprCondition[] = ("o.is_priority_appr=0");
            }

            // Combine
            $conditions[] = '(' . implode(' OR ', $priorityApprCondition) . ')';
        }

        if ($exclude && in_array($userType, array(4, 14))) {
            $conditions[] = ("(o.exclude='" . $exclude . "')");
        }

        if ($newConstExpert && in_array($userType, array(4))) {
            $conditions[] = ("(o.new_construction_expert='" . $newConstExpert . "')");
        }

        if ($diversityStatus && in_array($userType, array(4))) {
            $conditions[] = ("(o.diversity_status='" . $diversityStatus . "')");
        }

        if ($active) {
            $conditions[] = ("(u.active='" . $active . "')");
        }

        if ($dateType == 'joined' && ($dateFrom || $dateTo)) {
            if ($dateFrom && $dateTo) {
                $conditions[] = sprintf("(u.register_date >= '%s 00:00:00' AND u.register_date <= '%s 23:59:59')", $dateFrom, $dateTo);
            } elseif ($dateFrom && !$dateTo) {
                $conditions[] = sprintf("(u.register_date >= '%s 00:00:00')", $dateFrom);
            } elseif ($dateTo && !$dateFrom) {
                $conditions[] = sprintf("(u.register_date <= '%s 23:59:59')", $dateTo);
            }
        }

        // Add State condition
        if ($state) {
            $stateCondition = array();
            if (!in_array($userType, array(4, 14))) {
                $stateCondition[] = ("LOWER(o.state) = " . \DB::connection()->getPdo()->quote(strtolower($state)) . "");
            }

            $stateCondition[] = ("LOWER(o.comp_state) = " . \DB::connection()->getPdo()->quote(strtolower($state)) . "");

            // Combine
            $conditions[] = '(' . implode(' OR ', $stateCondition) . ')';
        }

        // Add Company condition
        if ($company) {
            $companyCondition = array();
            // Other
            $companyCondition[] = ("LOWER(o.company) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($company) . '%') . "");
            // Combine
            $conditions[] = '(' . implode(' OR ', $companyCondition) . ')';
        }

        // Add City condition
        if ($city) {
            $cityCondition = array();
            if (!in_array($userType, array(4, 14))) {
                $cityCondition[] = ("LOWER(o.city) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($city) . '%') . "");
            }
            $cityCondition[] = ("LOWER(o.comp_city) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($city) . '%') . "");

            // Combine
            $conditions[] = '(' . implode(' OR ', $cityCondition) . ')';
        }

        // Add Address condition
        if ($address) {
            $addressCondition = array();
            if (!in_array($userType, array(4, 14))) {
                $addressCondition[] = ("LOWER(o.address) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($address) . '%') . "");
            }
            $addressCondition[] = ("LOWER(o.comp_address) LIKE " . \DB::connection()->getPdo()->quote('%' . strtolower($address) . '%') . "");

            // Combine
            $conditions[] = '(' . implode(' OR ', $addressCondition) . ')';
        }

        // Add group condition
        if ($groupExclude && $client) {
            $clientCondition = array();
            $clientCondition[] = ("ue.groupid IN (" . implode(',', $client) . ")");

            // Combine
            $conditions[] = '(' . implode(' OR ', $clientCondition) . ')';
        } elseif (!$groupExclude && $client) {
            $clientCondition = array();
            $clientCondition[] = ("g.id IN (" . implode(',', $client) . ")");

            // Combine
            $conditions[] = '(' . implode(' OR ', $clientCondition) . ')';
        }

        // User Type
        if ($userType) {
            $conditions[] = ("u.user_type='" . intval($userType) . "'");

            // Add County condition
            if ($county) {
                $countyCondition = array();

                if ($userType == 4) {
                    // Appraiser
                    $countyCondition[] = ("LOWER(c.County) = " . \DB::connection()->getPdo()->quote(strtolower($county)) . "");
                } elseif ($userType == 14) {
                    // Agent
                    $countyCondition[] = ("LOWER(c.County) = " . \DB::connection()->getPdo()->quote(strtolower($county)) . "");
                } else {
                    // Other
                    $countyCondition[] = ("LOWER(c.County) = " . \DB::connection()->getPdo()->quote(strtolower($county)) . "");
                }

                // Combine
                $conditions[] = '(' . implode(' OR ', $countyCondition) . ')';
            }
        }

        // Add joins
        $sql .= implode("\n", $joins);

        // Add conditions
        if (count($conditions)) {
            $where = implode(" AND ", $conditions);
            $sql .= "\nWHERE " . $where;
        }



        // Run query and return users
        // 
   
        $users = \DB::select($sql);

        if ($users) {
            $defHeaders = getUserReportManagerHeaders();
            $visibleHeaders = array();
            $usedHeaders = array();

            if ($columns && count($columns)) {
                foreach ($defHeaders as $k => $v) {
                    if (in_array($k, $columns)) {
                        $visibleHeaders[$k] = $v;
                    }
                }
            }

            if (count($visibleHeaders)) {
                $usedHeaders = $visibleHeaders;
            } else {
                $usedHeaders = $defHeaders;
            }

            $revertedUsedHeaders = array_flip($usedHeaders);

            $rows = array();

            foreach ($users as $user) {
                // For appraisers
                if (in_array('total_avg_turn_time', $revertedUsedHeaders) || in_array('accepted_avg_turn_time', $revertedUsedHeaders) || in_array('qc_avg_turn_time', $revertedUsedHeaders) || in_array('uw_avg_turn_time', $revertedUsedHeaders)) {
                    $appraiserAverage = $this->getAppraiserAverageTurnTime($user->id);
                    
                }

                // For appraisers
                if (in_array('90_total_avg_turn_time', $revertedUsedHeaders) || in_array('90_accepted_avg_turn_time', $revertedUsedHeaders) || in_array('90_qc_avg_turn_time', $revertedUsedHeaders) || in_array('90_uw_avg_turn_time', $revertedUsedHeaders)) {
                    $appraiserAverage90Days = $this->getAppraiserAverageTurnTimeDaysRange($user->id);
                }
                
                $rows[] = array(
                    'id' => $user->id,
                    'email' => $user->email,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    //'exclude' => removeCommas($user->exclude),
                    'user_type' => $this->getUserTypeTitle($user),
                    'joined' => date('m/d/Y', strtotime($user->register_date)),

                    'active' => $user->active,
                    'groupid' => in_array('groupid', $revertedUsedHeaders) ? $this->getUserGroupTitle($user->groupid) : '',
                    'notes' => $user->notes,
                    'referral' => $user->referral,
                    'is_priority' => $user->is_priority_appr ? 'Yes' : 'No',
                    'appr_priority_invite_accepted_date' => $user->appr_priority_invite_accepted_date ? date('m/d/Y g:i A', $user->appr_priority_invite_accepted_date) : '',
                    'appraisal_software' => $user->appr_software,

                    // Other
                    'title' => $user->title,
                    'phone' => $this->removeCommas(trim($user->phone . ' ' . $user->phoneext)),
                    'mobile' => $this->removeCommas($user->mobile),
                    'fax' => $this->removeCommas($user->fax),
                    'address' => $this->removeCommas(trim($user->address . ' ' . $user->address1)),
                    'city' => $this->removeCommas($user->city),
                    'state' => $this->removeCommas($user->state),
                    'county' => in_array('county', $revertedUsedHeaders) ? $this->removeCommas($this->getCountyByZip($user->comp_zip)) : '',
                    'zip' => $this->removeCommas($user->zip),
                    'comp_address' => $this->removeCommas(trim($user->comp_address . ' ' . $user->comp_address1)),
                    'comp_city' => $this->removeCommas($user->comp_city),
                    'comp_state' => $this->removeCommas($user->comp_state),
                    'comp_zip' => $this->removeCommas($user->comp_zip),
                    'company' => $this->removeCommas($user->company),
                    'date_company_joined' => isset($user->date_company_joined) ? date('m/d/Y', $user->date_company_joined) : 'N/A',
                    'client_pricing_version' => in_array('client_pricing_version', $revertedUsedHeaders) ? $this->removeCommas( isset($user->client_pricing_version) ?: 'Custom' ) : '',
                    'last_order_placed_date' => in_array('last_order_placed_date', $revertedUsedHeaders) ? $this->removeCommas($this->lastAppraisalOrderPlacedDate($user->id)) : '',

                    // Appraiser/agent
                    'payable_address' => $this->removeCommas(trim($user->payable_address . ' ' . $user->payable_address1)),
                    'payable_city' => $this->removeCommas($user->payable_city),
                    'payable_state' => $this->removeCommas($user->payable_state),
                    'payable_zip' => $this->removeCommas($user->payable_zip),
                    'payable_company' => $this->removeCommas($user->payable_company),

                    'ein' => $this->removeCommas($user->ein),
                    'ins_company' => $this->removeCommas($user->ins_company),
                    'ins_expire' => $this->removeCommas($user->ins_expire),

                    'eando_each' => $this->removeCommas($user->ins_amt),
                    'eando_aggregate' => $this->removeCommas($user->ins_amt_agg),

                    // Appraiser
                    'fha' => $this->removeCommas($user->fha),
                    'fha_fee' => $this->removeCommas($user->fha_fee),
                    'software_charge' => $this->removeCommas($user->software_charge),
                    'is_away' => (isset($user->is_away) ? ($user->is_away ? 'Yes' : 'No') : ''),
                    'away_start_date' => (isset($user->away_start_date) && $user->away_start_date ? (date('m/d/Y', $user->away_start_date)) : ''),
                    'away_end_date' => (isset($user->away_end_date) && $user->away_end_date ? (date('m/d/Y', $user->away_end_date)) : ''),

                    'is_state_compliance_marked' => (isset($user->is_state_compliance_marked) ? ($user->is_state_compliance_marked ? 'Yes' : 'No') : ''),
                    'state_compliance_date' => (isset($user->state_compliance_date) && $user->state_compliance_date ? (date('m/d/Y', $user->state_compliance_date)) : ''),
                    'appr_state_compliance_approved' => (isset($user->appr_state_compliance_approved) ? ($user->appr_state_compliance_approved ? 'Yes' : 'No') : ''),

                    'total_orders_placed' => in_array('total_orders_placed', $revertedUsedHeaders) ? $this->getOrdersPlacedByUserId($user->id) : 0,
                    'total_orders_accepted' => in_array('total_orders_accepted', $revertedUsedHeaders) ? $this->getOrdersAcceptedByUserId($user->id) : 0,
                    'total_orders_completed' => in_array('total_orders_completed', $revertedUsedHeaders) ? $this->getApprOrdersCompletedByUserId($user->id) : 0,

                    'date_orders_placed' => (in_array('date_orders_placed', $revertedUsedHeaders) || ($dateFrom || $dateTo)) ? $this->getUserReportOrdersPlacedByUserId($user->id, $dateFrom, $dateTo) : 0,
                    'date_orders_accepted' => (in_array('date_orders_accepted', $revertedUsedHeaders) || ($dateFrom || $dateTo)) ? $this->getUserReportOrdersAcceptedByUserId($user->id, $dateFrom, $dateTo) : 0,
                    'date_orders_completed' => (in_array('date_orders_completed', $revertedUsedHeaders) || ($dateFrom || $dateTo)) ? $this->getUserReportApprOrdersCompletedByUserId($user->id, $dateFrom, $dateTo) : 0,

                    'total_avg_turn_time' => in_array('total_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage['placed'], 2) : 0,
                    'accepted_avg_turn_time' => in_array('accepted_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage['total'], 2) : 0,

                    '90_total_avg_turn_time' => in_array('90_total_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage90Days['placed'], 2) : 0,
                    '90_accepted_avg_turn_time' => in_array('90_accepted_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage90Days['total'], 2) : 0,

                    'qc_avg_turn_time' => in_array('qc_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage['qc'], 2) : 0,
                    'uw_avg_turn_time' => in_array('uw_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage['uw'], 2) : 0,

                    '90_qc_avg_turn_time' => in_array('90_qc_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage90Days['qc'], 2) : 0,
                    '90_uw_avg_turn_time' => in_array('90_uw_avg_turn_time', $revertedUsedHeaders) ? number_format($appraiserAverage90Days['uw'], 2) : 0,

                    'last_order_accepted_date' => in_array('last_order_accepted_date', $revertedUsedHeaders) ? $this->getAppraiserLastOrderAccepetedDate($user->id) : 0,
                    'last_order_completed_date' => in_array('last_order_completed_date', $revertedUsedHeaders) ? $this->getAppraiserLastOrderCompletedDate($user->id) : 0,

                    'fha_license' => in_array('fha_license', $revertedUsedHeaders) ? isset($user->license_number) ? $user->license_number : 'N/A' : '',
                    'fha_license_expiration' => in_array('fha_license_expiration', $revertedUsedHeaders) ? isset($user->fha_license_expiration) ? $user->fha_license_expiration : 'N/A' : '',
                    'asc_license' => in_array('asc_license', $revertedUsedHeaders) ? isset($user->asc_license_number) ? $user->asc_license_number : 'N/A' : '',
                    'asc_license_expiration' => in_array('asc_license_expiration', $revertedUsedHeaders) && isset($user->asc_license_expiration) ? $user->asc_license_expiration : '',
                    'asc_license_type' => in_array('asc_license_type', $revertedUsedHeaders) && isset($user->asc_license_type) ? $this->getAppraiserLicenseTypeName($user->asc_license_type) : '',
                    'state_license' => in_array('state_license', $revertedUsedHeaders) && isset($user->state_license) ? $user->state_license : '',
                    'state_license_type' => in_array('state_license_type', $revertedUsedHeaders) && isset($user->state_license_type) ? $user->state_license_type : '',
                    'state_license_state' => in_array('state_license_state', $revertedUsedHeaders) && isset($user->state_license_state) ? $user->state_license_state : '',
                    'state_license_expiration' => in_array('state_license_expiration', $revertedUsedHeaders) && isset($user->state_license_expiration) ? $user->state_license_expiration : '',

                    'diversity_status' => (isset($user->diversity_status) ? ($user->diversity_status ? 'Yes' : 'No') : ''),
                    'diversity_type' => in_array('diversity_type', $revertedUsedHeaders) ? ($this->getDiversityStatusInformation()[$user->diversity_type] ?? '') : '',
                    'diversity_agency_type' => in_array('diversity_agency_type', $revertedUsedHeaders) ? ($this->getDiversityAgencyType()[$user->diversity_agency_type] ?? '') : '',
                    'diversity_agency_type_other' => (isset($user->diversity_agency_type_other) ? $user->diversity_agency_type_other : ''),
                    'diversity_agency_certify_agency' => (isset($user->diversity_agency_certify_agency) ? $user->diversity_agency_certify_agency : ''),
                    'diversity_agency_certificate_number' => (isset($user->diversity_agency_certificate_number) ? $user->diversity_agency_certificate_number : ''),
                    'diversity_agency_effective_date' => (isset($user->diversity_agency_effective_date) ? $user->diversity_agency_effective_date : ''),
                    'diversity_agency_expiration_date' => (isset($user->diversity_agency_expiration_date) ? $user->diversity_agency_expiration_date : ''),
                );
            }
            
            // Do we need to look
            $needTo = false;
            $needToKeys = array();
            foreach ($needToKeys as $key) {
                if (in_array($key, $revertedUsedHeaders)) {
                    $needTo = true;
                    break;
                }
            }

            // We have dates
            if (($dateFrom || $dateTo) && in_array($dateType, array('placed', 'accepted', 'completed'))) {
                $needTo = true;
            }
           
            // Loop each one and remove if we need to
            if ($needTo) {
                if ($rows && count($rows)) {
                    foreach ($rows as $id => $row) {
                        $remove = false;

                        // We need to make sure we have at least one
                        if ($dateType == 'placed') {
                            if ($row['date_orders_placed'] < $minCount) {
                                $remove = true;
                            }
                        } elseif ($dateType == 'accepted') {
                            if ($row['date_orders_accepted'] < $minCount) {
                                $remove = true;
                            }
                        } elseif ($dateType == 'completed') {
                            if ($row['date_orders_completed'] < $minCount) {
                                $remove = true;
                            }
                        }

                        // Remove?
                        if ($remove) {
                            unset($rows[$id]);
                        }
                    }
                }
            }

            // Build content
            $content = array();

            // Add headers
            $content[] = implode(',', $usedHeaders);

            // Create new row set
            $items = [];
            foreach ($rows as $row) {
                $r = array();
                foreach ($row as $i => $m) {
                    if (array_key_exists($i, $usedHeaders)) {
                        $r[$i] = $m;
                    }
                }
                $items[] = $r;
            }
            
            return [$items, $usedHeaders];
            
        }     
    }
    
    function getAppraiserAverageTurnTime($apprId, $returnFullAdjusted=false, $dateFrom=null, $dateTo=null, $debug=false) 
    {
    

        $totalCompleted = $this->getAppraiserTotalCompletedFullFiles($apprId);
        
        $items = ['submit' => 0, 'qc' => 0, 'uw' => 0, 'inspection' => 0, 'total' => 0, 'adjusted' => 0, 'placed' => 0, 'accepted' => 0, 'accepted_to_scheduled' => 0, 'scheduled_to_delivered' => 0];

        // dd($totalCompleted);
        // Early exit
        if(!$totalCompleted) {
            return $items;
        }
    }

    function getAppraiserTotalCompletedFullFiles($apprId) 
    {
        
        $row = \DB::select("SELECT COUNT(id) as total FROM appr_order WHERE status IN (6,14,17) AND date_delivered IS NOT NULL AND acceptedby='".$apprId."' AND appr_type IN (1,4,15,71,72,73,83,84,86)");
        return $row[0]->total;
    }


    function getAppraiserAverageTurnTimeDaysRange($apprId, $days=90, $returnFullAdjusted=false) 
    {
    
        $totalCompleted = $this->getAppraiserTotalCompletedFullFilesDaysRange($apprId, $days);
        $items = array('submit' => 0, 'qc' => 0, 'uw' => 0, 'inspection' => 0, 'total' => 0, 'adjusted' => 0, 'placed' => 0, 'accepted' => 0, 'accepted_to_scheduled' => 0, 'scheduled_to_delivered' => 0);

        $time = strtotime(sprintf("-%s days", $days));

        // Early exit
        if(!$totalCompleted) {
            return $items;
        }

    }

    function getAppraiserTotalCompletedFullFilesDaysRange($apprId, $days=90) 
    {
        
        $time = strtotime(sprintf("-%s days", $days));
        $row = \DB::select("SELECT COUNT(id) as total FROM appr_order WHERE status IN (6,14,17) AND date_delivered >= '".date('Y-m-d H:i:s', $time)."' AND acceptedby='".$apprId."' AND appr_type IN (1,4,15,71,72,73,83,84,86)");
        return $row[0]->total;
    }

    function getUserTypeTitle($row) {
        switch($row->user_type) {
            // Appraiser
            case 4:
                return 'Appraiser';
            break;
            
            // Real estate agent
            case 14:
                return 'Agent';
            break;
            
            // client
            case 5:
                return 'Client';
            break;

            // admin
            case 1:
                return 'Admin';
            break;
            
            // Anything else
            default:
                return 'N/A';
            break;
        }
    }

    function removeCommas($t, $encode=false)
    {
        $t = str_replace(array(',', '"', ';'), array(' ', '', ''), $t);
        $t = preg_replace("/\n/", '', $t);
        if ($encode) {
            $t = '"'.$t.'"';
        }
        return $t;
    }

    function getCountyByZip($zip) {

      $row = \DB::selectOne("SELECT * FROM zip_code WHERE zip_code='".$zip."'");
      return $row ? ucwords(strtolower($row->county)) : null;
    }

    function lastAppraisalOrderPlacedDate($id)
    {
        $row = \DB::selectOne("SELECT id, ordereddate FROM appr_order WHERE orderedby=:id ORDER BY id DESC", [':id' => $id]);
  
        return $row ? date('m/d/Y', strtotime($row->ordereddate)) : '';
    }

    function getOrdersPlacedByUserId($userId) 
    {
        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_order WHERE orderedby='".$userId."' AND status NOT IN (9,10,20)");
        return $row->total;
    }

    function getOrdersAcceptedByUserId($userId) 
    {
        global $db;
        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_order WHERE acceptedby='".$userId."' AND status NOT IN (9)");
        return $row->total;
    }


    function getApprOrdersCompletedByUserId($userId) {
        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_order WHERE acceptedby='".$userId."' AND status IN (6,14,17)");
        return $row->total;
    }


    function getUserReportOrdersPlacedByUserId($userId, $dateFrom, $dateTo)
    {

        $where = '';

        if ($dateFrom && $dateTo) {
            $where = sprintf(" AND ordereddate >= '%s 00:00:00' AND ordereddate <= '%s 23:59:59'", $dateFrom, $dateTo);
        } elseif ($dateFrom && !$dateTo) {
            $where = sprintf(" AND ordereddate >= '%s 00:00:00'", $dateFrom);
        } elseif ($dateTo && !$dateFrom) {
            $where = sprintf(" AND ordereddate <= '%s 23:59:59'", $dateTo);
        }

        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_order WHERE orderedby='" . $userId . "' AND status NOT IN (9)" . $where);
        return $row->total;
    }

    function getUserReportOrdersAcceptedByUserId($userId, $dateFrom, $dateTo)
    {

        if ($dateFrom && $dateTo) {
            $where = sprintf(" AND ordereddate >= '%s 00:00:00' AND ordereddate <= '%s 23:59:59'", $dateFrom, $dateTo);
        } elseif ($dateFrom && !$dateTo) {
            $where = sprintf(" AND ordereddate >= '%s 00:00:00'", $dateFrom);
        } elseif ($dateTo && !$dateFrom) {
            $where = sprintf(" AND ordereddate <= '%s 23:59:59'", $dateTo);
        }

        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_order WHERE acceptedby='" . $userId . "' AND status NOT IN (9)" . $where);
        return $row->total;
    }


    function getUserReportApprOrdersCompletedByUserId($userId, $dateFrom, $dateTo)
    {

        if ($dateFrom && $dateTo) {
            $where = sprintf(" AND ordereddate >= '%s 00:00:00' AND ordereddate <= '%s 23:59:59'", $dateFrom, $dateTo);
        } elseif ($dateFrom && !$dateTo) {
            $where = sprintf(" AND ordereddate >= '%s 00:00:00'", $dateFrom);
        } elseif ($dateTo && !$dateFrom) {
            $where = sprintf(" AND ordereddate <= '%s 23:59:59'", $dateTo);
        }

        $row = \DB::selectOne("SELECT COUNT(id) as total FROM appr_order WHERE acceptedby='" . $userId . "' AND status IN (6)" . $where);
        return $row->total;
    }

    function getAppraiserLastOrderAccepetedDate($apprId) 
    {
        
        $row = \DB::selectOne("SELECT accepteddate FROM appr_order WHERE acceptedby='".$apprId."' ORDER BY accepteddate DESC");
        if($row) {
            return date('m/d/Y', strtotime($row->accepteddate));
        }
        return null;
    }

    function getAppraiserLastOrderCompletedDate($apprId) {
        
        $row = \DB::selectOne("SELECT date_delivered FROM appr_order WHERE acceptedby='".$apprId."' AND date_delivered IS NOT NULL AND status IN (6) ORDER BY date_delivered DESC");
        if($row) {
            return date('m/d/Y', strtotime($row->date_delivered));
        }
        return null;
    }


    function getAppraiserLicensesTypes() {
        return array(
            1 => 'Licensed',
            2 => 'Certified General',
            3 => 'Certified Residential',
            4 => 'Transitional License',
        );
    }

    function getAppraiserLicenseTypeName($k) {
        $types = $this->getAppraiserLicensesTypes();
        return isset($types[$k]) ? $types[$k] : $k;
    }


    function getDiversityStatusInformation() {
        $types =  explode("\n", Setting::getSetting('diversity_types'));
        $list = [];

        if(!empty($types)) {
            foreach($types as $item) {
                list($key, $value) = explode('=', $item);
                $list[$key] = $value;
            }

            if(!empty($list)) {
                return $list;
            }
        }

        return array(
            'mbe' => 'Minority Owned Business Enterprise',
            'wbe' => 'Woman Owned Business Enterprise',
            'glbt' => 'Gay, Lesbian, Bisexual, Transgender',
            'vbe' => 'Veteran Owned Business Enterprise',
            'other' => 'Other',
        );
    }

    function getDiversityAgencyType() {
        return array(
            'self' => 'Self Certified',
            'agency' => 'Agency Certified',
        );
    }

    function getUserGroupTitle($groupId) {
        
        $group = \DB::selectOne("SELECT descrip FROM user_groups WHERE id = " . intval($groupId));
        return $group ? $group->descrip : 'N/A';
    }
}  

