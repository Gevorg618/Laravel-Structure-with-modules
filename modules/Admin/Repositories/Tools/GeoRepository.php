<?php

namespace Modules\Admin\Repositories\Tools;

use App\Models\Geo\Region;
use App\Models\Geo\State;
use App\Models\Geo\Timezone;
use Yajra\DataTables\Datatables;
use DB;

class GeoRepository
{   

    /**
     * get regions
     *
     * @return mixed
     */
    public function regions()
    {
      return Region::get();
    }

    /**
     * get timezones
     *
     * @return mixed
     */
    public function timezones()
    {
      return Timezone::get();
    }

    /**
     * get states
     *
     * @return mixed
     */
    public function states()
    {
      return State::get();
    }

    /**
     * get state by state id
     *
     * @param $id  state id
     * @return mixed
     */
    public function state($id)
    {
      return State::where('id', $id)->with('stateAdjacent')->first();
    }

    /**
     * create state,  attached with timezone region and adjacent states
     *
     * @return mixed
     */
    public function createState($request)
    {
        $stateData = $request->except('state_2');
        $adjacentStatesId = $request->only('state_2');

        try {
            DB::beginTransaction();

            $createdState = State::create($stateData);
            
            $this->attachAdjacentStates($createdState, $adjacentStatesId);

            DB::commit();

            $response = [
                'success' => true,
                'message' => 'State successfully Created'
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
     * update custom page
     *
     * @param integer $id
     * @param array $data
     *
     * @return mixed
     */
    public function update($id, $request)
    {
        $state = $this->state($id);

        if ($state) {

            $stateData = $request->except('state_2','_token', '_method');
            $adjacentStatesId = $request->only('state_2');

            $requestData = $request->all();

            try {
                DB::beginTransaction();

                State::where('id', $id)->update($stateData);
                
                $detachAdjacentStates = $this->detachAdjacentStates($state, $state->stateAdjacent->pluck('id'));
                
                if ($detachAdjacentStates) {
                    $this->attachAdjacentStates($state, $adjacentStatesId);
                }

                DB::commit();
                $response = [
                    'success' => true,
                    'message' => 'GEO Successfully Updated',
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

        } else {

            $response = [
                    'success' => false,
                    'message' => "GEO is not found."
                ];
            return $response;
        }
        
    }

    /**
     * delete
     *
     * @param integer $id state id
     * @return mixed
     */
    public function delete($id)
    {
        $state = $this->state($id); 

        if ($state) {
        
            $detachAdjacentStates = $this->detachAdjacentStates($state, $state->stateAdjacent->pluck('id'));
        
            return State::where('id', $id)->delete();
        
        }  else {
        
            return false;
        }
    }

    /**
     * adjacent states attach to state
     *
     * @return mixed
     */
    public function attachAdjacentStates($createdState, $adjacentStatesId)
    {
        foreach ($adjacentStatesId as $key => $stateId) {
            $createdState->stateAdjacent()->attach($stateId);
        }
        return true;
    }

    /**
     * adjacent states detach to state
     *
     * @return mixed
     */
    public function detachAdjacentStates($createdState, $adjacentStatesId)
    {
        foreach ($adjacentStatesId as $key => $stateId) {
            $createdState->stateAdjacent()->detach($stateId);
        }
        return true;
    }
    
    /**
     * get states with timezones , adjacent states and region for dataTable
     *
     * @return array $customPagesDataTables
     */
    public function statesDataTables()
    {
        $states = State::with(['stateTimezone', 'stateRegion', 'stateAdjacent'])->get();
        
        $statesDataTables = Datatables::of($states)
                ->editColumn('options', function ($state) {
                    return view('admin::tools.geo-manager.partials._options', ['state' => $state])->render();
                })
                ->editColumn('state', function ($state) {
                    return $state->state . ' ('.$state->abbr.')';
                })
                ->editColumn('region', function ($state) {
                    return $state->stateRegion['name'];
                })
                ->editColumn('timezone', function ($state) {
                    return  $state->stateTimezone['name']; 
                })
                ->editColumn('adjacent_states', function ($state) {
                    $adjacentStates = '';
                    foreach ($state->stateAdjacent as $adjacentState) {
                        $adjacentStates .= $adjacentState->state. ',' ;
                    }
                    return $adjacentStates;
                })
                ->rawColumns(['options'])
                ->make(true);
                
        return $statesDataTables;
    }
   
}