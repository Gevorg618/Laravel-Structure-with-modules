<?php

namespace Modules\Admin\Repositories\AutoSelectPricing;

use DB;
use App\Models\AutoSelectPricing\AutoSelectPricingGroupFee;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;
use App\Models\Geo\State;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Carbon\Carbon;

class AutoSelectPricingGroupFeeRepository
{   

    /**
     * the array that head must be
     * @var int
     */
    public $headsArray = ['Type', 'Amount', 'FHA Amount', 'Fee Type'];

    /**
     * Object of TypesRepository class
     *
     * @var typesRepo
     */
    private $typesRepo;

    /**
     * the number of columns that must be in uploaded file
     * @var int $fileColumnsCount
     */
    private $fileColumnsCount = 4;

    /**
     * Get now time
     * @var date  $timestampNow
     */
    private $timestampNow;

    /**
     * AppraiserFeePricingRepository constructor.
     */
    public function __construct()
    {
        $this->timestampNow = strtotime("now");
        $this->typesRepo = new TypesRepository();
    }

	/**
     * get all not exitsing groups
     *
     * @return collection
     */
    public function notExistingGroups()
    {   
       return Client::doesntHave('autoSelectPricingGroupFees')->select(['id', 'descrip'])->get();
    }

    /**
     * Create New Group Pricing Version
     *
     * @param array $requestData
     * @return collection
     */
    public function createGroupPricingVersionFees($requestData)
    {   
    	$appraisalTypes = Type::get(['id']);
    	$states = State::get(['abbr']);
    	$insertData = [];

    	foreach ($states as $state) {
    		foreach ($appraisalTypes as $appraisalType) {
    			$requestData['state'] = $state->abbr;
	            $requestData['appr_type'] = $appraisalType->id;
	            $requestData['fee_type'] = 'fixed';
    			$insertData[] = $requestData; 
    		}
    	}
    	try {
    		AutoSelectPricingGroupFee::insert($insertData);
    		
    	} catch (\Exception $e) {
		    $message = $e->getMessage();
		    $response = [
		       'success' => false,
		       'message' => $message
		   ];
		   return $response;
    	}

    	$response = [
            'success' => true,
            'message' => 'The Pricing Groups Fee Successfully Created',
        ];

        return $response;
    }

    /**
     * delete pricing groups
     *
     * @param integer $id 
     * @return mixed
     */
    public function deletePricingGroup($id)
    { 
        
        try {
            
            AutoSelectPricingGroupFee::where('group_id' , $id)->forceDelete();
            $response = [
                'success' => true,
                'message' => 'Pricing Group Fee Successfully Deleted',
            ];

            return $response; 

        } catch (\Exception $exception) {
            DB::roleBack();

            $message = $exception->getMessage();
            $response = [
                'success' => false,
                'message' => $message
            ];
            return $response;
        }

    }

    /**
     * get total records
     *
     * @return collection
     */
    public function suppostedToBe()
    {   
       return Type::count() * State::count();
    }

    /**
     * get created groups
     *
     * @return collection
     */
    public function createdGroups()
    {   
       return AutoSelectPricingGroupFee::groupBy('group_id')->get();
    }

    /**
     * get group
     *
     * @param  int $id group id
     * @return collection
     */
    public function group($id)
    {   
       return AutoSelectPricingGroupFee::where('group_id', $id)->groupBy('group_id')->first();
    }

    /**
     * get group
     *
     * @param  int $id (group id)
     * @param  string $stateAbbr (state abbr)
     * @return collection
     */
    public function pricingGroupByState($id, $stateAbbr)
    {
       $group =  AutoSelectPricingGroupFee::where('group_id', $id);
       if ($stateAbbr) {
            return $group->where('state', $stateAbbr)->get();
       } else {
            return $group->get();
       }
       
    }

    /**
     * get group
     *
     * @param  int $id (group id)
     * @param  string $stateAbbr (state abbr)
     * @return collection
     */
    public function pricingGroupByTypeCreatedArray($groups)
    {

       $typesArray = [];
        foreach ($groups as $group) {
            $typesArray[$group->appr_type] = ['amount' => $group->amount, 'fhaamount' => $group->fhaamount, 'fee_type' => $group->fee_type];
        }
        return $typesArray;
    }

    /**
     * get group
     *
     * @param int $id (group id)
     * @return collection
     */
    public function updatePricingGroupVersion($groupId, $stateAbbr, $requestData)
    {
       
       foreach ($requestData['fee_pricing_group'][$stateAbbr] as $abbrTypeId => $data) {

            try {
                $data['last_updated_date'] = $requestData['last_updated_date'];
                $data['last_updated_by'] = $requestData['last_updated_by'];
                AutoSelectPricingGroupFee::where('group_id', $groupId)->where('state', $stateAbbr)->where('appr_type', $abbrTypeId)->update($data); 

            } catch (\Exception $exception) {

                $message = $exception->getMessage();
                $response = [
                    'success' => false,
                    'message' => $message
                ];

                return $response;
            }
        }

        $response = [
            'success' => true,
            'message' => 'Pricing Group Fee Successfully Updated',
        ];

        return $response; 
    }

    /**
     * create lines for template
     *
     * @param $types
     * @param $state
     * @return array
     */
    public function createLinesForTemplate($types, $pricingGroupsTypes)
    {
        $lines[] = $this->headsArray;
        foreach ($types as $typeId => $type) {
            $amount = (isset($pricingGroupsTypes[$typeId])) ? $pricingGroupsTypes[$typeId]['amount'] : "0.00";
            $fhaAmount = (isset($pricingGroupsTypes[$typeId])) ? $pricingGroupsTypes[$typeId]['fhaamount'] : "0.00";
            $feeType = (isset($pricingGroupsTypes[$typeId])) ? $pricingGroupsTypes[$typeId]['fee_type'] : "fixed";
            $lines[] = [
                $typeId.'|'.$type,
                $amount,
                $fhaAmount,
                $feeType
            ];
        }

        return $lines;
    }

    /**
     * get group fee pricing by states
     *
     * @param  integer $groupId
     * @param array $states
     * 
     * @return mixed
     */
    public function getByStates($groupId, $states)
    {
        return AutoSelectPricingGroupFee::where('group_id', $groupId)->whereIn('state', $states)->get();
    }

    /**
     * validate data from csv file and update or insert new data
     *
     * @param  integer $groupId 
     * @param array $states
     * @param file $fileData
     * 
     * @return array
     */
    public function validateCsvAndStore($groupId, $states, $fileData)
    {
        $heads = $fileData[0];
        $types = $this->typesRepo->getTypes();

        //check if the heads column match to the right heads
        $checkIfRightHeadsUploaded = array_diff($this->headsArray, $heads);

        if ($checkIfRightHeadsUploaded) {

            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => 'Sorry, There the number of columns does not match the number of expected columns.',
            ];

            return $result;
        }

        //check if the file is not empty
        unset($fileData[0]);
        if (!$fileData) {
            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => 'Sorry, There are not records to import.',
            ];

            return $result;
        }

        $versionPricingGroupFees = $this->getByStates($groupId, $states);

        $inserted = 0;
        $updated  = 0;
        $insertArray = [];

        DB::beginTransaction();
        
        foreach ($states as $state) {

            // check if group fee pricing already exists
            $existing = $versionPricingGroupFees->first(function ($item) use ($state) {
                return  $item->state == $state;
            });

            foreach ($fileData as $fee) {

                //check if the counts of columns of the uploaded file are right
                if (count($fee) != $this->fileColumnsCount) {
                    continue;
                }

                $type      = $fee[0];
                $amount    = $fee[1];
                $fhaAmount = $fee[2];
                $feeType   = $fee[3];

                //check if isset appraisal type ID
                $explodeType = explode('|', $type);
                if (!isset($explodeType[0])) {
                    continue;
                }
                $typeId = $explodeType[0];

                // check if type exists in appraiser types
                if (!$types->where('id', $typeId)->first()) {
                    continue;
                }

                //check if the data should be interted or updated
                if ($existing && $existing->where('appr_type', $typeId)->first()) {

                    try {

                            $requestData['fee_pricing_group'][$state] = [
                                'amount'    => floatval($amount),
                                'fhaamount' => floatval($fhaAmount),
                                'fee_type' => $feeType,
                                'last_updated_date' => $this->timestampNow,
                                'last_updated_by' => admin()->id
                            ];

                            // call function edit 
                            $this->updatePricingGroupVersion($groupId, $state, $requestData);

                    } catch (\Exception $exception) {
                            
                            DB::rollBack();
                            $result = [
                                'success' => '0',
                                'type'    => 'error',
                                'message' => $exception->getMessage(),
                            ];

                            return $result;
                    }

                    $updated++;

                } else {

                    $insertArray[] = [
                        'group_id' => $groupId,
                        'state'     => $state,
                        'appr_type' => $typeId,
                        'amount'    => floatval($amount),
                        'fhaamount' => floatval($fhaAmount),
                        'fee_type' => $feeType,
                        'created_by' => admin()->id,
                        'created_date' => $this->timestampNow,
                        'last_updated_date' => $this->timestampNow,
                        'last_updated_by' => admin()->id
                    ];

                    $inserted++;
                }

            }
        }

        try {

            AutoSelectPricingGroupFee::insert($insertArray);

        } catch (\Exception $exception) {

            DB::rollBack();

            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ];

            return $result;
        }

        DB::commit();

        $result = [
            'success' => '1',
            'type'    => 'success',
            'message' => sprintf("Import Complete. Imported %s Updated %s", $inserted, $updated),
        ];

        return $result;
    }

}