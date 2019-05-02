<?php

namespace Modules\Admin\Repositories\AutoSelectPricing;

use App\Models\AutoSelectPricing\ApprFeePricing;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Repositories\Customizations\TypesRepository;

class AppraiserFeePricingRepository
{
    /**
     * the array that head must be
     * @var int
     */
    public $headsArray = ['Type', 'Amount', 'FHA Amount'];
    /**
     * the number of columns that must be in uploaded file
     * @var int
     */
    private $fileColumnsCount = 3;
    private $appraiserFeePricing;
    private $typesRepo;

    /**
     * AppraiserFeePricingRepository constructor.
     */
    public function __construct()
    {
        $this->appraiserFeePricing = new ApprFeePricing();
        $this->typesRepo           = new TypesRepository();
    }

    /**
     * validate data from csv file and update or insert new data
     *
     * @param $states
     * @param $fileData
     * @return array
     */
    public function validateCsvAndStore($states, $fileData)
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
        $appraisalPricingFees = $this->getByStates($states);
        $inserted = 0;
        $updated  = 0;
        $insertArray = [];
        DB::beginTransaction();
        foreach ($states as $state) {
            // check if appraisal fee pricing already exists
            $existing = $appraisalPricingFees->first(function ($item) use ($state) {
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
                        $columns = [
                            'appr_type' => $typeId,
                            'state'     => $state,
                        ];
                        $params = [
                            'amount'    => floatval($amount),
                            'fhaamount' => floatval($fhaAmount),
                        ];
                        $this->edit($columns, $params);
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
                        'state'     => $state,
                        'appr_type' => $typeId,
                        'amount'    => floatval($amount),
                        'fhaamount' => floatval($fhaAmount),
                    ];
                    $inserted++;
                }

            }
        }
        try {
            $this->appraiserFeePricing->insert($insertArray);
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

    /**
     * get appraisal fee pricing by states
     *
     * @param array $states
     * @return mixed
     */
    public function getByStates($states)
    {
        return $this->appraiserFeePricing->whereIn('state', $states)->get();
    }

    /**
     * update appraiser pricing fee
     *
     * @param $data
     * @param $params
     * @return mixed
     */
    public function edit($data, $params)
    {
        return $this->appraiserFeePricing->where($data)->update($params);
    }

    /**
     * create lines for template
     *
     * @param $types
     * @param $state
     * @return array
     */
    public function createLinesForTemplate($types, $state)
    {
        $apprFeesPricing = $this->getByStates([$state]);
        $apprFeesPricingArray = $this->createApprFeesPricingArray($apprFeesPricing);
        $lines[] = $this->headsArray;
        foreach ($types as $typeId => $type) {
            $amount = (isset($apprFeesPricingArray[$typeId])) ? $apprFeesPricingArray[$typeId]['amount'] : "0.00";
            $fhaAmount = (isset($apprFeesPricingArray[$typeId])) ? $apprFeesPricingArray[$typeId]['fhaamount'] : "0.00";
            $lines[] = [
                $typeId.'|'.$type,
                $amount,
                $fhaAmount,
            ];
        }

        return $lines;
    }

    /**
     * create array for appr fee pricing
     *
     * @param $apprFeesPricing
     *
     * @return array
     */
    public function createApprFeesPricingArray($apprFeesPricing)
    {
        $apprFeesPricingArray = [];
        foreach ($apprFeesPricing as $fee) {
            $apprFeesPricingArray[$fee->appr_type] = ['amount' => $fee->amount, 'fhaamount' => $fee->fhaamount];
        }

        return $apprFeesPricingArray;
    }

    /**
     * get appraiser pricing fees of state
     *
     * @param $state
     * @return array
     */
    public function geByOneState($state)
    {
        $apprFeesPricing = $this->getByStates([$state]);
        $result = $this->createApprFeesPricingArray($apprFeesPricing);

        return $result;
    }

    /**
     * destroy appraiser fee pricing by state
     *
     * @param $state
     * @return mixed
     */
    public function destroyByState($state)
    {
        return $this->appraiserFeePricing->where('state', $state)->delete();
    }


    public function insertManyAppraiserFeePricing($state, $apprFeePricing)
    {
        $insertArray = [];
        foreach ($apprFeePricing as $typeId => $fee) {
            $insertArray[] = [
                'state'     => $state,
                'appr_type' => $typeId,
                'amount'    => floatval($fee['amount']),
                'fhaamount' => floatval($fee['fhaamount']),
            ];
        }

        $this->appraiserFeePricing->insert($insertArray);
    }

}