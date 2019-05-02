<?php

namespace Modules\Admin\Http\Controllers\Integrations\FNC;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;
use App\Models\Customizations\{Status, Type, LoanType, LoanReason, PropertyType, OccupancyStatus, Addenda};
use App\Models\Integrations\FNC\{FNCApprTypes, FNCContactTypes, FNCLoanReason, FNCLoanTypes, FNCPropertyTypes, FNCStatuses, FNCStatusRelation, FNCLoanReasonRelation, FNCPropertyTypesRelation, FNCLoanTypesRelation, FNCApprTypesRelation};

class FNCController extends AdminBaseController {

    /**
    * Index
    * @param $request
    * @return view
    */
    public function index(
            FNCStatuses $fncStatuses,
            Status $status,
            FNCLoanReason $fncLoanReason,
            LoanReason $loanReason,
            FNCPropertyTypes $fncPropertyTypes,
            PropertyType $propertyType,
            FNCLoanTypes $fncLoanTypes,
            LoanType $loanType,
            FNCApprTypes $fncApprTypes,
            Type $type
        )
    {
        $statusesView = $this->statusesView($fncStatuses, $status);
        $apprTypesView = $this->appraisalTypesView($fncApprTypes, $type, $propertyType);
        $loanTypesView = $this->loanTypesView($fncLoanTypes, $loanType, $loanReason);
        $loanReasonView = $this->loanReasonView($fncLoanReason, $loanReason);
        $propertyTypes = $this->propertyTypes($fncPropertyTypes, $propertyType);

        return view('admin::integrations.fnc.index',
            compact(
                'statusesView',
                'loanReasonView',
                'loanTypesView',
                'apprTypesView',
                'propertyTypes'
            )
        );
    }

    /**
    * statusesView
    * @param 
    * @return view
    */
    private function statusesView($fncStatuses, $status)
    {
        $saveStatuses = FNCStatusRelation::all();
        $fncStatuses = $fncStatuses->allStatuses();
        $statuses = $status->allStatuses();

        return view('admin::integrations.fnc.partials._statuses',
            compact(
                'saveStatuses',
                'fncStatuses',
                'statuses'
            )
        );
    }

    /**
    * updateStatuses
    * @param $request
    * @return void
    */
    public function updateStatuses(Request $request)
    {
        $inputs = $request->status;
        FNCStatusRelation::truncate();
        foreach($inputs as $fncId => $internalId) {
            if($internalId) {
                FNCStatusRelation::create([
                    'fnc_status_id' => $fncId,
                    'lni_status_id' => $internalId
                ]);
            }
        }
        Session::flash('success', 'Statuses Successfully Updated!');
        return redirect()->back();
    }

    /**
    * appraisalTypesView
    * @param $mercuryApprType, $type
    * @return view
    */
    private function appraisalTypesView ($fncApprTypes, $type, $propertyType)
    {
        $fncApprTypes = $fncApprTypes->allTypes();
        $internalApprTypes = $type->allTypes();
        $internalPropertyTypes = $propertyType->allTypes();;
        $savedData = FNCApprTypesRelation::all();
        $internalOccupancyStatuses = OccupancyStatus::all();
        $internalAddendas = Addenda::all();

        return view('admin::integrations.fnc.partials._appraisal_types',
            compact(
                'fncApprTypes',
                'internalApprTypes',
                'savedData',
                'internalPropertyTypes',
                'internalOccupancyStatuses',
                'internalAddendas'
            )
        );
    }

    /**
    * updateApprTypes
    * @param $request
    * @return void
    */
    public function updateApprTypes(Request $request)
    {
        $apprTypes = $request->appr_type;
        FNCApprTypesRelation::truncate();

        foreach($apprTypes as $id => $typeId) {
            $addendas = isset($request->addendas[$id]) ? $request->addendas[$id] : null;
            if (
                    !empty($typeId) ||
                    !empty($request->prop_type[$id]) ||
                    !empty($request->occ_status[$id]) ||
                    !is_null($addendas)
                ) {
                FNCApprTypesRelation::create([
                    'fnc_type_id' =>  $id,
                    'lni_type_id' => $typeId,
                    'property_type_id' => $request->prop_type[$id],
                    'occ_type_id' => $request->occ_status[$id],
                    'addendas' => !is_null($addendas) ? implode($addendas, ",") : ''
                ]);
            }
        }
        Session::flash('success', 'Appraisal Types Successfully Updated!');
        return redirect()->back();
    }

    /**
    * loanTypesView
    * @param $mercuryLoanType, $loanReason
    * @return view
    */
    private function loanTypesView ($fncLoanTypes, $loanType, $loanReason)
    {
        $fncLoanTypes = $fncLoanTypes->allTypes();
        $loanTypes = LoanType::all();
        $loanReason = $loanReason->allReasons();
        $savedTypes = FNCLoanTypesRelation::all();

        return view('admin::integrations.fnc.partials._loan_types',
            compact(
                'fncLoanTypes',
                'loanTypes',
                'loanReason',
                'savedTypes'
            )
        );
    }

    /**
    * updateStatuses
    * @param $request
    * @return void
    */
    public function updateLoanType(Request $request)
    {
        $types = $request->type;
        $reason = $request->reason;

        FNCLoanTypesRelation::truncate();
        foreach($types as $id => $typeId) {
            if (!empty($typeId) || !empty($reason[$id])) {
                FNCLoanTypesRelation::create([
                    'fnc_type_id' => $id,
                    'lni_type_id' => $typeId,
                    'lni_reason_id' => $reason[$id]
                ]);
            }
        }
        Session::flash('success', 'Loan Types Successfully Updated!');
        return redirect()->back();
    }

    /**
    * loanReasonView
    * @param $mercuryLoanReason, $loanReason
    * @return view
    */
    private function loanReasonView($fncLoanReason, $loanReason)
    {
        $fncLoanReasons = $fncLoanReason->allReasons();
        $internalTypes = $loanReason->allReasons();
        $savedReasons = FNCLoanReasonRelation::all();

        return view('admin::integrations.fnc.partials._loan_reasons',
            compact(
                'fncLoanReasons',
                'internalTypes',
                'savedReasons'
            )
        );
    }

    /**
    * updateLoanReason
    * @param $request
    * @return void
    */
    public function updateLoanReason(Request $request)
    {
        $inputs = $request->reason;
        FNCLoanReasonRelation::truncate();
        foreach($inputs as $reasonyId => $internalId) {
            if($internalId) {
                FNCLoanReasonRelation::create([
                    'fnc_type_id' => $reasonyId,
                    'lni_type_id' => $internalId
                ]);
            }
        }
        Session::flash('success', 'Loan Reasons Successfully Updated!');
        return redirect()->back();
    }

        /**
    * loanReasonView
    * @param $mercuryLoanReason, $loanReason
    * @return view
    */
    private function propertyTypes($fncPropertyTypes, $propertyType)
    {
        $fncPropertyTypes = $fncPropertyTypes->allTypes();
        $internalTypes = $propertyType->allTypes();
        $savedTypes = FNCPropertyTypesRelation::all();

        return view('admin::integrations.fnc.partials._property_types',
            compact(
                'fncPropertyTypes',
                'internalTypes',
                'savedTypes'
            )
        );
    }

    /**
    * updateLoanReason
    * @param $request
    * @return void
    */
    public function updatePropertyTypes(Request $request)
    {
        $inputs = $request->property;
        FNCPropertyTypesRelation::truncate();
        foreach($inputs as $propertyId => $internalId) {
            if($internalId) {
                FNCPropertyTypesRelation::create([
                    'fnc_type_id' => $propertyId,
                    'lni_type_id' => $internalId
                ]);
            }
        }
        Session::flash('success', 'Loan Reasons Successfully Updated!');
        return redirect()->back();
    }

}
