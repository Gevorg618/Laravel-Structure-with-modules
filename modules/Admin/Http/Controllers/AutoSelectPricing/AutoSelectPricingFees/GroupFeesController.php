<?php

namespace Modules\Admin\Http\Controllers\AutoSelectPricing\AutoSelectPricingFees;

use Modules\Admin\Http\Controllers\AutoSelectPricing\AutoSelectPricingFees\BaseFeesController;
use Carbon\Carbon;
use Session;
use App\Models\AutoSelectPricing\AutoSelectPricingVersionFee;
use Modules\Admin\Http\Requests\AutoSelect\AutoSelectPricingGroupFeeRequest;
use Modules\Admin\Http\Requests\AutoSelect\AutoSelectImportPricingVersionFeeRequest;
use Illuminate\Http\Request;
use DB;

class GroupFeesController extends BaseFeesController
{

    /**
     * Create a new instance of GroupFeesController class.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }   

    /** 
     * POST /admin/autoselect-pricing/version-fees/store
     *
     * Create New Group Pricing Version
     *
     * @return view
     */
    public function store(AutoSelectPricingGroupFeeRequest $request)
    {
        $requestData = $request->except('_token');
       
        $createdPricingVersionFee = $this->pricingGroupFeeRepo->createGroupPricingVersionFees($requestData);

        if ($createdPricingVersionFee['success']) {

            Session::flash('success', $createdPricingVersionFee['message']);

            return redirect()->route('admin.autoselect.pricing.fees.index');
        
        } else {

            Session::flash('error', $createdPricingVersionFee['message']);

            return redirect()->route('admin.autoselect.pricing.fees.index');
        }

    }

    /**
     * POST /admin/autoselect-pricing/version-fees/import-csv
     * 
     * insert or update the autoselect pricing group  fees from csv file
     *
     * @param integer $groupId Auto Select Version Pricing Fee group id
     * @param AutoSelectImportPricingVersionFeeRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import($groupId, AutoSelectImportPricingVersionFeeRequest $request)
    {
        $states = $request->input('state');
        $file  = $request->file('fees');
        $checkState = $this->statesRepository->getMultipleStatesByAbbr($states);

        if ($checkState->count() != count($states)) {

            return redirect()->back()->with('error', 'Sorry, that state is not valid.');
        }

        try {

            DB::beginTransaction();

            $data =   $this->excel->loadFile($file);

            if (isset($data['success']) && $data['success'] == '0') {
                return redirect()->back()->with($data['type'], $data['message']);
            }

            if (!$data) {
                return redirect()->back()->with('error', 'Sorry, There are not records to import.');
            }

            $result = $this->pricingGroupFeeRepo->validateCsvAndStore($groupId, $states, $data);

            DB::commit();

            return redirect()->back()->with($result['type'], $result['message']);

        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->with('error', $exception->getMessage());
        }
    }

    /**
     * GET /admin/autoselect-pricing/version-fees/delete
     *
     * Delete Pricing Group
     *
     * @param integer $id
     *
     * @return view
     */
    public function destroy($id)
    {
    
        $isDeleted = $this->pricingGroupFeeRepo->deletePricingGroup($id);

        if ($isDeleted['success']) {

            Session::flash('success', $isDeleted['message']);
            
            return redirect()->route('admin.autoselect.pricing.fees.index');

        } else {

            Session::flash('error', $isDeleted['message']);
            
            return redirect()->route('admin.autoselect.pricing.fees.index');
        }
    }


    /**
     * GET /admin/autoselect-pricing/version-fees/group-state
     *
     * get Pricing Group states view page
     *
     * @param integer $id
     *
     * @return view
     */
    public function state($id, $stateAbbr = null, Request $request)
    {   

        $pricingGroups = $this->pricingGroupFeeRepo->pricingGroupByState($id, $stateAbbr);

        if (!$stateAbbr) {

            $all = true;
            $states = $this->statesRepository->getStates();
            $group = $this->pricingGroupFeeRepo->group($id);
            return view('admin::auto_select_pricing.version-fees.group-fee.edit', compact('all', 'states', 'group')); 
        }

        if ($pricingGroups) {

            $all = false;
            $state = $this->statesRepository->getStateByAbbr($stateAbbr);
            $group = $this->pricingGroupFeeRepo->group($id);
            $objectId = $group->group_id;
            $types = $this->typesRepository->createTypesArray();
            $pricingGroupsTypes = $this->pricingGroupFeeRepo->pricingGroupByTypeCreatedArray($pricingGroups);
            
            if ($request->ajax()) {
                $result  = view('admin::auto_select_pricing.version-fees.partials._form-group', compact('pricingGroupsTypes', 'state', 'group', 'types', 'objectId'))->render();
                return response()->json($result);
            }

            return view('admin::auto_select_pricing.version-fees.group-fee.edit', compact('pricingGroupsTypes', 'state', 'group', 'types', 'all', 'objectId'));

        } else {

            Session::flash('error', 'Sorry, We could not find that record.');
            
            return redirect()->route('admin.autoselect.pricing.fees.index');
        }  
    }

    /**
     * GET /admin/autoselect-pricing/version-fees/group-states
     *
     * get Pricing Group states view page
     *
     * @param integer $id
     *
     * @return view
     */
    public function states($id)
    {   
        $group = $this->pricingGroupFeeRepo->group($id);

        if ($group) {

            $states = $this->statesRepository->getStates();

            return view('admin::auto_select_pricing.version-fees.group-fee.states', compact('states', 'group'));

        } else {

            Session::flash('error', 'Sorry, We could not find that record.');
            
            return redirect()->route('admin.autoselect.pricing.fees.index');
        }  
    }

    /**
     * PUT /admin/autoselect-pricing/version-fees/group-state
     *
     * get Pricing Group states view page
     *
     * @param integer $id
     *
     * @return view
     */
    public function stateUpdate($groupId, $stateAbbr, AutoSelectPricingGroupFeeRequest $request)
    {   
        
        $requestData = $request->except('_token','_method');

        $isUpdated = $this->pricingGroupFeeRepo->updatePricingGroupVersion($groupId, $stateAbbr, $requestData);

        if ($isUpdated['success']) {

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $isUpdated['message']]);
            }

            Session::flash('success', $isUpdated['message']);
            return redirect()->route('admin.autoselect.pricing.fees.index');
        
        } else {

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $isUpdated['message']]);
            }

            Session::flash('error', $isUpdated['message']);

            return redirect()->route('admin.autoselect.pricing.fees.index');
        }
    }

    /**
     * GET /admin/autoselect-pricing/version-fees/download
     *
     * get Pricing Group states view page
     *
     * @param integer $id
     * @param  string $stateAbbr
     * @return view
     */
    public function download($groupId, $stateAbbr)
    {   

        $checkState = $this->statesRepository->getStateByAbbr($stateAbbr);

        if (!$checkState) {
            Session::flash('error', 'Sorry, that state is not valid.');

            return redirect()->back();
        }

        $pricingGroups = $this->pricingGroupFeeRepo->pricingGroupByState($groupId, $stateAbbr);

        if ($pricingGroups) {

            // get all types by array
            $types = $this->typesRepository->createTypesArray();

            // get pricing group version by group_id and state abbr and created array by key appr_type
            $pricingGroupsTypes = $this->pricingGroupFeeRepo->pricingGroupByTypeCreatedArray($pricingGroups);

            // get lines for csv 
            $lines = $this->pricingGroupFeeRepo->createLinesForTemplate($types, $pricingGroupsTypes);

            // template name set state
            $template = $pricingGroups->first()->client->descrip.'-'.$checkState->state.'('.$checkState->abbr.')';
            $ext = 'csv';

            $result =  $this->excel->handleExport($lines, $template, $ext);

            if ($result['success'] == '0') {
                Session::flash($result['type'], $result['message']);

                return redirect()->back();
            }

        } else {

            Session::flash('error', 'Sorry, We could not find that record.');
            
            return redirect()->route('admin.autoselect.pricing.fees.index');
        } 
    }

    
}