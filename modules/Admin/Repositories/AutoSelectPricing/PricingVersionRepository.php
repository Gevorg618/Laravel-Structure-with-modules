<?php

namespace Modules\Admin\Repositories\AutoSelectPricing;

use App\Models\Appraisal\{StatePricingVersion, ApprStatePrice};
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Yajra\DataTables\Datatables;
use App\Models\Clients\Client;

class PricingVersionRepository
{   

    /**
     * Object of StatesRepository class
     *
     * @var stateRepo
     */
    private $stateRepo; 

    /**
     * Object of TypesRepository class
     *
     * @var typeRepo
     */
    private $typeRepo;

    /**
     * PricingVersionRepository constructor.
     */
    public function __construct()
    {
        $this->stateRepo = new StatesRepository();
        $this->typeRepo = new TypesRepository();
    }


    

    /**
     * @param $requestType, $skip, $take
     * 
     * @return response
     */
    public function getPricingVersions()
    {   
        return StatePricingVersion::all();
    }

    /**
     * @param $requestType, $skip, $take
     * 
     * @return response
     */
    public function data($type = null, $skip = null, $take = null)
    {   
        $response = false;

        switch ($type) {
            case 'pricing':
                    $response = $this->pricingVersionData($skip , $take);
               break;
            case 'custom_pricing':
                    $response = $this->customPricingDatatable($skip , $take);
               break;
            case 'transferred_orders':
                    $response = $this->transferredOrdersDataTables($fromDate, $toDate, $skip , $take);
               break;     
            case 'daily_stats':
                    $response = $this->dailyDataTables($fromDate, $toDate, $skip , $take);
                break;    
        }

        return $response;
    }


    /**
     * get place orders for dataTable
     *
     * @return array $teamDataTables
     */
    public function customPricingDatatable($skip , $take)
    {
                
        $apprStatePrices = ApprStatePrice::whereHas('groupData')->groupBy('groupid')->get();
        
        $statesCount = $this->stateRepo->getStatesCount();
        $apprTypesCount = $this->typeRepo->getTypesCount();

        $apprStatePriceRawCount = $apprStatePrices->count();

        return Datatables::of($apprStatePrices)
                ->editColumn('client', function ($apprStatePrice) {
                    return $apprStatePrice->groupData->descrip;
                })
                ->editColumn('set_records', function ($apprStatePrice) {
                    return $apprStatePrice->groupData->set_pricing_versions_count;
                })
                ->editColumn('empty_records', function ($apprStatePrice) {
                    return $apprStatePrice->groupData->empty_pricing_versions_count;
                })
                ->editColumn('suppose_records', function ($apprStatePrice) use ($statesCount, $apprTypesCount){
                    return $statesCount * $apprTypesCount;
                })
                ->editColumn('records', function ($apprStatePrice) use ($statesCount, $apprTypesCount){
                    return $apprStatePrice->groupData->appr_state_price_count;
                })
                 ->editColumn('options', function ($apprStatePrice) {
                    return view('admin::auto_select_pricing.pricing-version.partials._options_custom', compact('apprStatePrice'))->render();
                })
                ->rawColumns(['options'])
                ->setTotalRecords($apprStatePriceRawCount)
                ->make(true);
    }

    /**
     * get place orders for dataTable
     *
     * @return array $teamDataTables
     */
    public function pricingVersionData($skip , $take)
    {
        
        $pricingVersions = StatePricingVersion::withCount(['setPricingVersions', 'emptyPricingVersions', 'clients', 'apprPricingVersions']);

        $statesCount = $this->stateRepo->getStatesCount();
        $apprTypesCount = $this->typeRepo->getTypesCount();

        $pricingVersionsRawCount = $pricingVersions->count();

        $pricingVersions = $pricingVersions->skip((int)$skip)->take((int)$take);
        
        return Datatables::of($pricingVersions)
                ->editColumn('title', function ($pricingVersion) {
                    return $pricingVersion->title;
                })
                ->editColumn('position', function ($pricingVersion) {
                    return $pricingVersion->pos;
                })
                ->editColumn('clients', function ($pricingVersion) {
                    return $pricingVersion->clients_count;
                })
                ->editColumn('set_records', function ($pricingVersion) {
                    return $pricingVersion->set_pricing_versions_count;
                })
                ->editColumn('empty_records', function ($pricingVersion) {
                    return $pricingVersion->empty_pricing_versions_count;
                })
                ->editColumn('suppose_records', function ($pricingVersion) use ($statesCount, $apprTypesCount){
                    return $statesCount * $apprTypesCount;
                })
                ->editColumn('records', function ($pricingVersion) {
                    return $pricingVersion->appr_pricing_versions_count;
                })
                 ->editColumn('options', function ($pricingVersion) {
                    return view('admin::auto_select_pricing.pricing-version.partials._options', compact('pricingVersion'))->render();
                })
                ->rawColumns(['options'])
                ->setTotalRecords($pricingVersionsRawCount)
                ->make(true);
    }

    /**
     * create Pricing
     * @return array 
     */
    public function createPricing($data)
    {
        $newPricing = StatePricingVersion::create(['title' => $data['title'], 'pos' => $data['pos']]);
        $newPricing->loanReasons()->sync($data['loan_reason']);
        return $newPricing ? true : false ;
        
    }

    /**
     * create Pricing
     * @return array 
     */
    public function getPricing($id)
    {
        return  StatePricingVersion::findOrFail($id);        
    }

    /**
     * create Pricing
     * @return array 
     */
    public function updatePricing($id, $data)
    {
        $pricing = $this->getPricing($id);
        $pricing->update(['title' => $data['title'], 'pos' => $data['pos']]); 
        $pricing->loanReasons()->sync($data['loan_reason']);  
        return $pricing ? true : false ;
    }

    /**
     * create Pricing
     * @return array 
     */
    function getPricingTemplateHeaders($sep, $apprTypes) {

        $headers = [];
        $headers['state'] = "State";

        foreach($apprTypes as $id => $type) {
            $headers[$id . '_conv'] = str_replace($sep, '', $type . ' - Conv');
            $headers[$id . '_fha'] = str_replace($sep, '', $type . ' - FHA');
        }
        
        return $headers;
    }


    /**
     * create Pricing
     * @return array 
     */
    function pricingDownload($id) {

       $pricing = $this->getPricing($id);

        if ($pricing) {

            $template = "";
            $headers = [];
            $rows = [];
            $sep = ",";
            $data = [];

            // Get states and appr types
            $apprTypes = $this->typeRepo->createTypesArray();
            $states = $this->stateRepo->getStates()->pluck('state', 'abbr');

            // Headers
            $headers = $this->getPricingTemplateHeaders($sep, $apprTypes);
            $headersCount = count($headers);

            // Get Values
            $values = [];
            $versionRows = $pricing->apprPricingVersions;
            
            if ($versionRows && count($versionRows)) {
                foreach ($versionRows as $r) {
                    $values[$r->state][$r->appr_type] = ['amount' => $r->amount, 'fha_amount' => $r->fha_amount];
                }
            }

            // Rows
            foreach ($states as $abbr => $state) {
                $line = [];
                $line[] = $abbr;
                foreach ($headers as $headerId => $headerTitle) {

                    if ($headerId == 'state') {
                        continue;
                    }

                    // Figure out type
                    $conv = strpos($headerId, '_conv') !== false;
                    $fha = strpos($headerId, '_fha') !== false;
                    if ($fha) {
                        $type = str_replace('_fha', '', $headerId);
                    } else {
                        $type = str_replace('_conv', '', $headerId);
                    }

                    $amount = '0.00';
                    if (isset($values[$abbr][$type])) {
                        if ($fha) {
                            $amount = $values[$abbr][$type]['fha_amount'];
                        } else {
                            $amount = $values[$abbr][$type]['amount'];
                        }
                    }

                    $line[] = $amount;
                }
                
                $rows[] = $line;
            }

            // Create template
            $data[] = implode($sep, $headers);
            foreach ($rows as $item) {
                $data[] = implode($sep, str_replace($sep, '', $item));
            }

            return ['data' => $data, 'title' => $pricing->title];

        } else {
            dd('not fount');
        }    
    }
    

    /**
     * create Pricing
     * @return array 
     */
    public function updatePricingAddenda($id, $data)
    {
        
        unset($data[0]);
        $addendas = [];

        foreach ($data as $key => $value) {
            $addendas[$key] = ['amount' => $value]; 
        }

        $pricing = $this->getPricing($id);
        $pricing->addendas()->sync($addendas);  

        return $pricing ? true : false ;
    }

    /**
     * create Pricing
     * @return array 
     */
    public function loadPricesByState($versionId, $state)
    {
        $list = [];
        $rows = \DB::select("SELECT appr_type, amount, fha_amount, loan_type FROM appr_state_price_version_row WHERE version_id=:id AND state=:state", [':id' => $versionId, ':state' => $state]);
        
        foreach ($rows as $row) {
            if ($row->loan_type == 0) {
                $list[$row->appr_type][1] = $row->amount;
                $list[$row->appr_type][2] = $row->fha_amount;
            } else {
                $list[$row->appr_type][$row->loan_type] = $row->loan_type == 2 ? $row->fha_amount : $row->amount;
            }
        }
        return $list;
    }

    /**
     * create Pricing
     * @return array 
     */
    public function loadPricesByClient($clientId, $state)
    {
        $list = [];
        $rows = \DB::select("SELECT appr_type, amount, fha_amount, loan_type FROM appr_state_price WHERE groupid=:id AND state=:state", [':id' => $clientId, ':state' => $state]);
        
        foreach($rows as $row) {
            if($row->loan_type == 0) {
                $list[$row->appr_type][1] = $row->amount;
                $list[$row->appr_type][2] = $row->fha_amount;
            } else {
                $list[$row->appr_type][$row->loan_type] = $row->loan_type == 2 ? $row->fha_amount : $row->amount;
            }
        }
        return $list;
    }


    /**
     * create Pricing
     * @return array 
     */
    public function getPricingStatePriceUpdate($id, $state, $prices)
    {
        $pricing = $this->getPricing($id);

        $data = $prices;
        
        try {

            \DB::table('appr_state_price_version_row')
                ->where('version_id', $id)
                ->where('state', $state)
                ->delete();

            foreach ($data as $appraisalId => $types) {
                foreach ($types as $typeId => $amount) {
                    if ((!$amount || $amount <= 0) ) {
                        continue;
                    }

                    $amount = $amount;
                    $fhaAmount = $data[$appraisalId][2];
                    if (in_array($typeId, [1, 2])) {
                        $amount = $data[$appraisalId][1];
                        $fhaAmount = $data[$appraisalId][2];
                    }

                    try {
                        
                        \DB::table('appr_state_price_version_row')->insert([['version_id' => $id, 'state' => $state, 'appr_type' => $appraisalId, 'loan_type' => $typeId, 'amount' => floatval($amount), 'fha_amount' => floatval($fhaAmount)]]);
                        
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }
                }
            }
        } catch (Exception $e) {
            
            return $e->getMessage();
        }
        return true;

    }

    /**
     * create Pricing
     * @return array 
     */
    public function storeApprStatePrices($clientId, $prices)
    {

        foreach ($prices as $state => $types) {
            foreach ($types as $type => $amounts) {
                \DB::table('appr_state_price')->insert(['groupid' => $clientId, 'state' => $state, 'appr_type' => $type, 'amount' => $amounts['amount'], 'fha_amount' => $amounts['fha_amount']]);
            }
        }
        return true;
    }

    /**
     * create Pricing
     * @return array 
     */
    public function getCustomSelectedPricingVersion($clientId)
    {

        $rows = \DB::select("SELECT loan_id FROM appr_state_price_client_loan_reason WHERE client_id=:id", [':id' => $clientId]);
        $list = [];
        foreach($rows as $row) {
            $list[] = $row->loan_id;
        }

        return $list;
    }


     /**
     * create Pricing
     * @return array 
     */
    public function loanReasonAllPublic()
    {
        $rows = \DB::select("SELECT * FROM loanpurpose ORDER BY descrip ASC");

        foreach($rows as $row) {
            $list[$row->id] = $row->descrip;
        }

        return $list;
    }


    /**
     * Save category
     * 
     */
    public  function apprStateSaveLoanReason($id, $loanReasons) {

        try {

            // Delete
            \DB::table('appr_state_price_client_loan_reason')->where('client_id', $id)->delete();

            // Members
            foreach($loanReasons as $key => $loanReasonId) {
                \DB::table('appr_state_price_client_loan_reason')->insert(['client_id' => $id, 'loan_id' => $loanReasonId]);
            }

        } catch(Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

     /**
     * create Pricing
     * @return array 
     */
    public function pricingClientDownload($clientId)
    {
        
        $row = \DB::table('user_groups')->where('id', $clientId)->first();
        $records =\DB::table('appr_state_price')->where('groupid', $clientId)->get();

        // Get Values
        $values = [];
        $rows = $records;

        foreach ($rows as $r) {
            $values[$r->state][$r->appr_type] = array('amount' => $r->amount, 'fha_amount' => $r->fha_amount);
        }

        $template = "";
        $headers = [];
        $rows = [];
        $sep = ",";
        $data = [];

        // Get states and appr types
        $apprTypes = $this->typeRepo->createTypesArray();
        $states = $this->stateRepo->getStates()->pluck('state', 'abbr');

        // Headers
        $headers = $this->getPricingTemplateHeaders($sep, $apprTypes);
        $headersCount = count($headers);

        // Rows
        foreach ($states as $abbr => $state) {
            $line = [];
            $line[] = $abbr;

            foreach ($headers as $headerId => $headerTitle) {
                if ($headerId == 'state') {
                    continue;
                }

                // Figure out type
                $conv = strpos($headerId, '_conv') !== false;
                $fha = strpos($headerId, '_fha') !== false;

                if ($fha) {
                    $type = str_replace('_fha', '', $headerId);
                } else {
                    $type = str_replace('_conv', '', $headerId);
                }

                $amount = '0.00';
                if (isset($values[$abbr][$type])) {
                    if ($fha) {
                        $amount = $values[$abbr][$type]['fha_amount'];
                    } else {
                        $amount = $values[$abbr][$type]['amount'];
                    }
                }

                $line[] = $amount;
            }
            $rows[] = $line;
        }

        // Create template
        $data[] = implode($sep, $headers);
        foreach ($rows as $item) {
            $data[] = implode($sep, str_replace($sep, '', $item));
        }

        return ['data' => $data, 'client' => $row];
    }


     /**
     * create Pricing
     * @return array 
     */
    public function getPricingClientPriceUpdate($id, $state, $prices)
    {

        try {

            // Delete
            \DB::table('appr_state_price')->where('groupid', $id)->where('state', $state)->delete();

            // Insert
            foreach($prices as $appraisalId => $types) {
                foreach($types as $typeId => $amount) {
                    if((!$amount || $amount <= 0) || !in_array($typeId, [1, 2])) {
                        continue;
                    }

                    $amount = $amount;
                    $fhaAmount = $prices[$appraisalId][2];
                    if(in_array($typeId, [1, 2])) {
                        $amount = $prices[$appraisalId][1];
                        $fhaAmount = $prices[$appraisalId][2];
                    }

                    \DB::table('appr_state_price')->insert(['groupid' => $id, 'state' => $state, 'appr_type' => $appraisalId, 'loan_type' => $typeId, 'amount' => $amount, 'fha_amount' => $fhaAmount]);
                }
            }

        } catch(Exception $e) {
            return $e->getMessage();
        }

        return true;

    }

     /**
     * create Pricing
     * @return array 
     */
    public function getSavedClientAddendas($id)
    {

        $rows = \DB::table('appr_state_client_pricing_addenda')->where('client_id', $id)->get();

        $list = [];

        foreach ($rows as $row) {
            $list[$row->addenda_id] = $row->amount;
        }

        return $list;

    }

    /**
     * create Pricing
     * @return array 
     */
    public function updatePricingClientAddenda($id, $addendas)
    {
        
        // Delete current
        \DB::table('appr_state_client_pricing_addenda')->where('client_id', $id)->delete();

        foreach ($addendas as $addendaId => $amount) {
            if ($amount !== '') {
                \DB::table('appr_state_client_pricing_addenda')->insert( ['client_id' => $id, 'addenda_id' => $addendaId, 'amount' => $amount]);
            }
        }

        return true;
    }

    /**
     * create Pricing
     * @return array 
     */
    public function deletePricingClient($id)
    {
        
        // Delete current
        \DB::table('appr_state_price')->where('groupid', $id)->delete();

        return true;
    }

    /**
     * create Pricing
     * @return array 
     */
    public function downloadTemplateExample()
    {
        
        $template = "";
        $headers = [];
        $rows = [];
        $sep = ",";
        $data = [];

        // Get states and appr types
        $apprTypes = $this->typeRepo->createTypesArray();
        $states = $this->stateRepo->getStates()->pluck('state', 'abbr');

        // Headers
        $headers = $this->getPricingTemplateHeaders($sep, $apprTypes);
        $headersCount = count($headers);

        // Rows
        foreach ($states as $abbr => $state) {
            $line = array();
            $line[] = $abbr;
            for ($i = 1; $i <= $headersCount - 1; $i++) {
                $line[] = '0.00';
            }
            $rows[] = $line;
        }

        // Create template
        $data[] = implode($sep, $headers);
        foreach ($rows as $row) {
            $data[] = implode($sep, str_replace($sep, '', $row));
        }

        return $data;
        
    }


    public function importVersion($versionId, $file)
    {

        $pricingVersion = $this->getPricing($versionId);

        if ($pricingVersion) {
            
            $versionName = $pricingVersion->title;

            // Check if the file has content
            $content = file_get_contents($file);

            // Parse file
            $data = [];
            $content = str_replace("\r", "\n", $content);
            $lines = explode("\n", $content);
            $headsCount = 0;
            $heads = [];
            $rows = [];
            $sep = ",";

            $apprTypes = $this->typeRepo->createTypesArray();

            // Headers
            $headers = $this->getPricingTemplateHeaders($sep, $apprTypes);
            $headersCount = count($headers);

            foreach ($lines as $line) {
                if ($headsCount == 0) {
                    $heads = explode($sep, $line);
                } else {
                    $rows[] = explode($sep, $line);
                }
                $headsCount++;
            }

            // Loop over the headers and grab the rows data
            $items = [];
            $apprTypeHeaders = [];
            $fullApprTypeHeaders = [''];
            
            $apprTypesFlip = array_flip($apprTypes);

            foreach ($heads as $headId => $headTitle) {
                if ($headId == '0') {
                    continue;
                }

                // Add into the list
                $fullApprTypeHeaders[] = $headTitle;

                // Skip if it has - FHA in it
                if (stripos($headTitle, ' - FHA') !== false) {
                    continue;
                }

                // Remove - Conv
                $headTitle = str_ireplace(' - Conv', '', $headTitle);
                
                if (!empty($apprTypesFlip[$headTitle])) {
                    $apprTypeHeaders[$apprTypesFlip[$headTitle]] = $headTitle;
                }
                

            }

            
            $values = [];
            foreach ($rows as $row) {
                $state = null;
                foreach ($row as $rowId => $rowValue) {
                    // Clean Value
                    $rowValue = $this->cleanValue($rowValue);

                    if ($rowId == 0) {
                        $state = $rowValue;
                        continue;
                    }

                    // Get name by index
                    $name = $fullApprTypeHeaders[$rowId];
                    // Get type by name
                    $type = $this->getStatePriceApprIdAndAmountTypeByName($name);

                    if (!empty($apprTypesFlip[$type['name']])) {
                        // Get appr id by name
                        $typeId = $apprTypesFlip[$type['name']];  

                         $values[$state][$typeId][$type['type']] = $rowValue;
                    }
                }
            }


            // Create version or update one?
            if ($pricingVersion) {

                $row = $pricingVersion;
                $id = $pricingVersion->id;

                $valueData = [];
                
                $pricingVersions = $row->apprPricingVersions;

                if ($pricingVersions && count($pricingVersions)) {
                    foreach ($pricingVersions as $r) {
                        $valueData[$r->state][$r->appr_type] = array('amount' => $r->amount, 'fha_amount' => $r->fha_amount);
                    }
                }

            } else {

                $valueData = [];
                // Create new
                \DB::table('appr_state_price_version')->insert(['title' => $versionName, 'created_date' => time()]);

                $id = \DB::table('appr_state_price_version')->lastInsertId();
            }

            $updated = 0;
            $imported = 0;

            if ($values && count($values)) {
                foreach ($values as $state => $types) {
                    foreach ($types as $type => $amounts) {
                        try {
                            // Check if the state->type exists
                            // if it does then update otherwise create
                            if (isset($valueData[$state][$type])) {
                                // Update
                                \DB::table('appr_state_price_version_row')->update(array('amount' => floatval($amounts['amount']), 'fha_amount' => floatval($amounts['fha_amount'])), 'version_id=:id AND state=:state AND appr_type=:type', array(':id' => $id, ':state' => $state, ':type' => $type));
                                $updated++;
                            } else {
                                // Create
                                \DB::table('appr_state_price_version_row')->insert(array('version_id' => $id, 'state' => $state, 'appr_type' => $type, 'amount' => floatval($amounts['amount']), 'fha_amount' => floatval($amounts['fha_amount'])));
                                $imported++;
                            }
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                }
            }

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Pricing Version Not Found!'];
        }
        
    }

    public function  cleanValue($t) {
        $t = str_replace('$', '', $t);
        $t = str_replace(',', '', $t);
        $t = str_replace('"', '', $t);
        return $t;
    }

    public function getStatePriceApprIdAndAmountTypeByName($name) {
        if(stripos($name, ' - FHA')!==false) {
            $name = str_ireplace(' - FHA', '', $name);
            return array('type' => 'fha_amount', 'name' => $name);
        } else {
            $name = str_ireplace(' - Conv', '', $name);
            return array('type' => 'amount', 'name' => $name);
        }
    }


    public function importClientVersion($clientId, $file)
    {
        
        // Check if the file has content
        $content = file_get_contents($file);
        if (!$content) {
            setFlash('error', 'Sorry, The file uploaded is empty.');
            moveto('admin/appr_state_prices.php');
        }

        // Parse file
        $data = [];
        $content = str_replace("\r", "\n", $content);
        $lines = explode("\n", $content);
        $count = 0;
        $heads = [];
        $rows = [];
        $sep = ",";

         $apprTypes = $this->typeRepo->createTypesArray();

        // Headers
        $headers = $this->getPricingTemplateHeaders($sep, $apprTypes);
        $headersCount = count($headers);

        foreach ($lines as $line) {
            if ($count == 0) {
                $heads = explode($sep, $line);
            } else {
                $rows[] = explode($sep, $line);
            }
            $count++;
        }

        // Loop over the headers and grab the rows data
        $items = [];
        $apprTypeHeaders = [];
        $fullApprTypeHeaders = [''];

        $apprTypesFlip = array_flip($apprTypes);

        foreach ($heads as $headId => $headTitle) {
            if ($headId == '0') {
                continue;
            }

            // Add into the list
            $fullApprTypeHeaders[] = $headTitle;

            // Skip if it has - FHA in it
            if (stripos($headTitle, ' - FHA') !== false) {
                continue;
            }
            
            if (!empty($apprTypesFlip[$headTitle])) {
                    $apprTypeHeaders[$apprTypesFlip[$headTitle]] = $headTitle;
                }

        }

        $values = [];
        foreach ($rows as $row) {
            $state = null;
            foreach ($row as $rowId => $rowValue) {
                // Clean Value
                $rowValue = $this->cleanValue($rowValue);

                if ($rowId == 0) {
                    $state = $rowValue;
                    continue;
                }

                // Get name by index
                $name = $fullApprTypeHeaders[$rowId];
                // Get type by name
                $type = $this->getStatePriceApprIdAndAmountTypeByName($name);
                if (!empty($apprTypesFlip[$type['name']])) {
                        // Get appr id by name
                        $typeId = $apprTypesFlip[$type['name']];  

                         $values[$state][$typeId][$type['type']] = $rowValue;
                    }
            }
        }



        $id = $clientId;
        $row = Client::findOrFail($id);
        $records = \DB::select("SELECT * FROM appr_state_price WHERE groupid='".$id."'");


        // Get Values
        $valueData = [];
        $pricingVersions = $records;

        if ($pricingVersions && count($pricingVersions)) {
            foreach ($pricingVersions as $r) {
                $valueData[$r->state][$r->appr_type] = array('amount' => $r->amount, 'fha_amount' => $r->fha_amount);
            }
        }

        $updated = 0;
        $imported = 0;
        if ($values && count($values)) {
            foreach ($values as $state => $types) {
                foreach ($types as $type => $amounts) {
                    try {
                        // Check if the state->type exists
                        // if it does then update otherwise create
                        if (isset($valueData[$state][$type])) {
                            // Update
                            \DB::table('appr_state_price')->update( array('amount' => $amounts['amount'], 'fha_amount' => $amounts['fha_amount']), 'groupid=:id AND state=:state AND appr_type=:type', array(':id' => $id, ':state' => $state, ':type' => $type));
                            $updated++;
                        } else {
                            // Create
                            \DB::table('appr_state_price')->insert(array('groupid' => $id, 'state' => $state, 'appr_type' => $type, 'amount' => $amounts['amount'], 'fha_amount' => $amounts['fha_amount']));
                            $imported++;
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }

            return true;
        
    }
}