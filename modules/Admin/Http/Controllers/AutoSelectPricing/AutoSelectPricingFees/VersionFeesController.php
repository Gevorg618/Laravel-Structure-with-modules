<?php

namespace Modules\Admin\Http\Controllers\AutoSelectPricing\AutoSelectPricingFees;

use Modules\Admin\Http\Controllers\AutoSelectPricing\AutoSelectPricingFees\BaseFeesController;
use Carbon\Carbon;
use Session;
use App\Models\AutoSelectPricing\AutoSelectPricingVersionFee;
use Modules\Admin\Repositories\AutoSelectPricing\AutoSelectPricingVersionFeeRepository;
use Modules\Admin\Http\Requests\AutoSelect\AutoSelectPricingVersionFeeRequest;
use Modules\Admin\Http\Requests\AutoSelect\AutoSelectImportPricingVersionFeeRequest;
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Modules\Admin\Helpers\Excel;
use Illuminate\Http\Request;
use DB;

class VersionFeesController extends BaseFeesController
{
    
    /**
     * Create a new instance of VersionFeesController class.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    } 

   	/**
     * GET /admin/autoselect-pricing/version-fees/version/states
     *
     * get Pricing Version states view page
     *
     * @param integer $id
     *
     * @return view
     */
    public function states($id)
    {   
    	
        $version = $this->pricingVersionFeeRepo->version($id);
        
        if ($version) {

            $states = $this->statesRepository->getStates();

            return view('admin::auto_select_pricing.version-fees.version-fee.states', compact('states', 'version'));

        } else {

            Session::flash('error', 'Sorry, We could not find that record.');
            
            return redirect()->route('admin.autoselect.pricing.fees.index');
        }  
    }

    /**
     * GET /admin/autoselect-pricing/version-fees/version/state
     *
     * get Pricing Version states view page
     *
     * @param integer $id
     *
     * @return view
     */
    public function state($id, $stateAbbr = null, Request $request)
    {   
    	
        $pricingVersions = $this->pricingVersionFeeRepo->pricingVersionByState($id, $stateAbbr);
        
        if (!$stateAbbr) {

            $all = true;
            $states = $this->statesRepository->getStates();
            $version = $this->pricingVersionFeeRepo->version($id);

            return view('admin::auto_select_pricing.version-fees.version-fee.edit', compact('all', 'states', 'version')); 
        }

        if ($pricingVersions) {

            $all = false;
            $state = $this->statesRepository->getStateByAbbr($stateAbbr);
            $version = $this->pricingVersionFeeRepo->version($id);
            $objectId = $version->pricing_version_id;
            $types = $this->typesRepository->createTypesArray();

            $pricingGroupsTypes = $this->pricingVersionFeeRepo->pricingVersionByTypeCreatedArray($pricingVersions);
            
            if ($request->ajax()) {
                $result  = view('admin::auto_select_pricing.version-fees.partials._form-group', compact('pricingGroupsTypes', 'state', 'version', 'types', 'objectId'))->render();
                return response()->json($result);
            }

            return view('admin::auto_select_pricing.version-fees.version-fee.edit', compact('pricingGroupsTypes', 'state', 'version', 'types', 'all', 'objectId'));

        } else {

            Session::flash('error', 'Sorry, We could not find that record.');
            
            return redirect()->route('admin.autoselect.pricing.fees.index');
        }  
    }

    /**
     * POST /admin/autoselect-pricing/version-fees/version/import-csv
     * 
     * insert or update the autoselect pricing group  fees from csv file
     *
     * @param integer $versionId Auto Select Version Pricing Fee group id
     * @param AutoSelectImportPricingVersionFeeRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import($versionId, AutoSelectImportPricingVersionFeeRequest $request)
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

            $result = $this->pricingVersionFeeRepo->validateCsvAndStore($versionId, $states, $data);

            DB::commit();

            return redirect()->back()->with($result['type'], $result['message']);

        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->with('error', $exception->getMessage());
        }
    }


     /**
     * GET /admin/autoselect-pricing/version-fees/download
     *
     * download version states
     *
     * @param integer $versionId
     * @param  string $stateAbbr
     * @return view
     */
    public function download($versionId, $stateAbbr)
    {   

        $checkState = $this->statesRepository->getStateByAbbr($stateAbbr);

        if (!$checkState) {
            Session::flash('error', 'Sorry, that state is not valid.');

            return redirect()->back();
        }

        $pricingVersions = $this->pricingVersionFeeRepo->pricingVersionByState($versionId, $stateAbbr);

        if ($pricingVersions) {

            // get all types by array
            $types = $this->typesRepository->createTypesArray();

            // get pricing group version by group_id and state abbr and created array by key appr_type
            $pricingVersionsTypes = $this->pricingVersionFeeRepo->pricingVersionByTypeCreatedArray($pricingVersions);

            // get lines for csv 
            $lines = $this->pricingVersionFeeRepo->createLinesForTemplate($types, $pricingVersionsTypes);
            
            // template name set states
            $template = $pricingVersions->first() ? $pricingVersions->first()->client->descrip.'-'.$checkState->state.'('.$checkState->abbr.')' : '--';
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

    /**
     * PUT /admin/autoselect-pricing/version-fees/version-state
     *
     * get Pricing version states view page
     *
     * @param integer $id
     *
     * @return view
     */
    public function stateUpdate($versionId, $stateAbbr, AutoSelectPricingVersionFeeRequest $request)
    {   
        
        $requestData = $request->except('_token','_method');

        $isUpdated = $this->pricingVersionFeeRepo->updatePricingVersion($versionId, $stateAbbr, $requestData);

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

}