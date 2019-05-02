<?php

namespace Modules\Admin\Repositories\AutoSelectPricing;

use DB;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;
use App\Models\Geo\State;
use App\Models\Appraisal\StatePricingVersion;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Carbon\Carbon;
use App\Models\AutoSelectPricing\AutoSelectPricingVersionFee;

class AutoSelectPricingVersionFeeRepository
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
     * get state pricing version fees
     *
     * @return collection
     */
    public function statePricingVersionFees()
    {   
       return StatePricingVersion::groupBy('title')->get();
    }

    /**
     * get version
     *
     * @param  int $id (version id)
     * @param  string $stateAbbr (state abbr)
     * @return collection
     */
    public function pricingVersionByState($id, $stateAbbr)
    {
       $version =  AutoSelectPricingVersionFee::where('pricing_version_id', $id);
       if ($stateAbbr) {
            return $version->where('state', $stateAbbr)->get();
       } else {
            return $version->get();
       }
       
    }

    /**
     * get gversion cerareted by array of types
     *
     * @param  int $id (version id)
     * @param  string $stateAbbr (state abbr)
     * @return collection
     */
    public function pricingVersionByTypeCreatedArray($versions)
    {

       $typesArray = [];
        foreach ($versions as $version) {
            $typesArray[$version->appr_type] = ['amount' => $version->amount, 'fhaamount' => $version->fhaamount, 'fee_type' => $version->fee_type];
        }
        return $typesArray;
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
     * get pricing version fee
     *
     * @param  int $id version id
     * @return collection
     */
    public function version($id)
    {   

       return StatePricingVersion::where('id', $id)->first();
    }

    /**
     * get group fee pricing by states
     *
     * @param  integer $versionId
     * @param array $states
     * 
     * @return mixed
     */
    public function getByStates($versionId, $states)
    {
        return AutoSelectPricingVersionFee::where('pricing_version_id', $versionId)->whereIn('state', $states)->get();
    }

    /**
     * get version
     *
     * @param int $id (version id)
     * @return collection
     */
    public function updatePricingVersion($versionId, $stateAbbr, $requestData)
    {
       
       foreach ($requestData['fee_pricing_group'][$stateAbbr] as $abbrTypeId => $data) {

            try {
                $data['last_updated_date'] = $requestData['last_updated_date'];
                $data['last_updated_by'] = $requestData['last_updated_by'];
                AutoSelectPricingVersionFee::where('pricing_version_id', $versionId)->where('state', $stateAbbr)->where('appr_type', $abbrTypeId)->update($data); 

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
            'message' => 'Pricing Version Fee Successfully Updated',
        ];

        return $response; 
    }

    /**
     * validate data from csv file and update or insert new data
     *
     * @param  integer $versionId 
     * @param array $states
     * @param file $fileData
     * 
     * @return array
     */
    public function validateCsvAndStore($versionId, $states, $fileData)
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

        $pricingVersionFees = $this->getByStates($versionId, $states);

        $inserted = 0;
        $updated  = 0;
        $insertArray = [];

        DB::beginTransaction();
        
        foreach ($states as $state) {

            // check if group fee pricing already exists
            $existing = $pricingVersionFees->first(function ($item) use ($state) {
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
                            $this->updatePricingVersion($versionId, $state, $requestData);

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
                        'pricing_version_id' => $versionId,
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

            AutoSelectPricingVersionFee::insert($insertArray);

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