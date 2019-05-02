<?php

namespace Modules\Admin\Repositories\AutoSelectPricing;

use DB;
use App\Models\AutoSelectPricing\AutoSelectClientTurnTime;
use App\Models\AutoSelectPricing\AutoSelectTurnTime;

class AutoSelectTurnTimeRepository
{   

	/**
     * get all auto slecect turn times
     *
     * @param array $request
     * @return collection
     */
    public function createClientTurnTimes($request)
    {
        $types = $request['types'];
        unset($request['types']);
        
        foreach ($types as $typeId => $turnTime) {

        	$request['type_id'] = $typeId;
        	$request['turn_time'] = $turnTime;
        	
        	try {
	            DB::beginTransaction();

	            $createdState = AutoSelectClientTurnTime::create($request);

	            DB::commit();

	        } catch (Exception $exception) {
	            DB::roleBack();
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
                'message' => 'Client Turn Times  Successfully Created',
            ];
        return $response;   
    }

    /**
     * update client turn times
     *
     * @param integer $clientId
     * @param  $request
     * @return collection
     */
    public function updateClientTurnTimes($clientId, $request)
    {
        $types = $request['types'];
        unset($request['types']);

        foreach ($types as $typeId => $turnTime) {

            $request['type_id'] = $typeId;
            $request['turn_time'] = $turnTime;
            
            try {
                DB::beginTransaction();
                $clientTurnTime = AutoSelectClientTurnTime::where('type_id', $typeId)->where('client_id', $clientId)->update($request);

                DB::commit();

            } catch (Exception $exception) {
                DB::roleBack();
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
                'message' => 'Client Turn Times  Successfully Updated',
            ];
        return $response; 
    }

    /**
     * update default turn times
     *
     * @param  $request
     * @return collection
     */
    public function updateDefaultTurnTimes($request)
    {
        $types = $request['types'];
        unset($request['types']);                

        foreach ($types as $typeId => $turnTime) {

            $request['type_id'] = $typeId;
            $request['turn_time'] = $turnTime;
            
            try {
                DB::beginTransaction();

                $clientTurnTime = AutoSelectTurnTime::where('type_id', $typeId)->update($request);

                DB::commit();

            } catch (Exception $exception) {
                DB::roleBack();
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
                'message' => 'Default Turn Times  Successfully Updated',
            ];
        return $response; 
    }

    /**
     * delete auto select client turn time
     *
     * @param integer $id 
     * @return mixed
     */
    public function deleteClientTurnTime($id)
    { 
        
        try {
            
            AutoSelectClientTurnTime::where('client_id' , $id)->delete();
            
            $response = [
                'success' => true,
                'message' => 'Client Turn Times  Successfully Deleted',
            ];

            return $response; 

        } catch (Exception $exception) {
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
     * get client all turn times
     *
     * @param integer $clientId
     * @return collection
     */
    public function clientTurnTimes($clientId)
    {
        return AutoSelectClientTurnTime::where('client_id', $clientId)->get();
    }

    /**
     * get default turn times
     *
     * @return collection
     */
    public function defaultTurnTimes()
    {
        return AutoSelectTurnTime::get();
    }
    
}