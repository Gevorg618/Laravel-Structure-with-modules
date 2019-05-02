<?php

namespace Modules\Admin\Http\Controllers\Integrations\MercuryNetwork;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;
use App\Models\Customizations\{Status, Type, LoanType, LoanReason, PropertyType, OccupancyStatus, Addenda};
use App\Models\Integrations\MercuryNetwork\{MercuryStatus, MercuryApprType, MercuryLoanType, MercuryLoanReason, MercuryStatusRelation, MercuryLoanTypeRelation, MercuryLoanReasonRelation, MercuryApprTypeRelation, MercuryEquityEdge};
use Modules\Admin\Http\Requests\Integrations\Mercury\EquityEdgeRequest;

class MercuryNetworkController extends AdminBaseController {


    protected $type;

    /**
     * MercuryNetworkController constructor.
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
    * Index
    * @param $request
    * @return view
    */
    public function index(
            Type $type,
            Status $status,
            LoanReason $loanReason,
            MercuryStatus $mercuryStatus,
            MercuryApprType $mercuryApprType,
            MercuryLoanType $mercuryLoanType,
            MercuryLoanReason $mercuryLoanReason,
            MercuryApprTypeRelation $mercuryApprTypeRelation
        )
    {

        $statusesView = $this->statusesView($mercuryStatus, $status);
        $apprTypesView = $this->appraisalTypesView($mercuryApprType, $type, $mercuryApprTypeRelation);
        $loanTypesView = $this->loanTypesView($mercuryLoanType, $loanReason);
        $loanReasonView = $this->loanReasonView($mercuryLoanReason, $loanReason);
        $equityEdgeView = $this->equityEdgeView();

        return view('admin::integrations.mercury_network.index',
            compact(
                'statusesView',
                'loanReasonView',
                'loanTypesView',
                'apprTypesView',
                'equityEdgeView'
            )
        );
    }

    /**
    * equityEdgeView
    * @param $mercuryEquityEdge
    * @return view
    */
    private function equityEdgeView()
    { 
        $mercuryEquityEdges = MercuryEquityEdge::getAll();
        
        return view('admin::integrations.mercury_network.partials._equity_edge',
            compact(
                'mercuryEquityEdges'
            )
        );
    }

    /**
    * equityEdgeView
    * @param $mercuryEquityEdge
    * @return view
    */
    public function equityEdgeCreate()
    {
        
        $apprTypes = multiselect(Type::all(), ['form', 'short_descrip'])->prepend('-- Select Appr Type --', '');
        $loanTypes = multiselect(LoanType::all(), 'descrip')->prepend('-- Select Loan Type --', '');
        $loanReasons = multiselect(LoanReason::all(), 'descrip')->prepend('-- Select Loan Reason --', '');

        return view('admin::integrations.mercury_network.equity-edge.create', compact('apprTypes', 'loanTypes', 'loanReasons'));
    }

    /**
    * equityEdgeStore
    * @param $mercuryEquityEdge
    * @return view
    */
    public function equityEdgeStore(EquityEdgeRequest $request)
    {
        MercuryEquityEdge::create($request->all());

        Session::flash('success', 'Record Successfully Created!');
        return redirect()->route('admin.integrations.mercury');
    }

    /**
    * equityEdgeDestroy
    * @param $mercuryEquityEdge
    * @return view
    */
    public function equityEdgeDestroy($id)
    {
        MercuryEquityEdge::find($id)->delete();

        Session::flash('success', 'Record Successfully Removed!');
        return redirect()->route('admin.integrations.mercury');
    }

    /**
    * equityEdgeDestroy
    * @param $mercuryEquityEdge
    * @return view
    */
    public function equityEdgeEdit($id)
    {
        $edge = MercuryEquityEdge::find($id);

        $apprTypes = multiselect(Type::all(), ['form', 'short_descrip'])->prepend('-- Select Appr Type --', '');
        $loanTypes = multiselect(LoanType::all(), 'descrip')->prepend('-- Select Loan Type --', '');
        $loanReasons = multiselect(LoanReason::all(), 'descrip')->prepend('-- Select Loan Reason --', '');

        return view('admin::integrations.mercury_network.equity-edge.edit', compact('apprTypes', 'loanTypes', 'loanReasons', 'edge'));
    }

    /**
    * equityEdgeDestroy
    * @param $mercuryEquityEdge
    * @return view
    */
    public function equityEdgeUpdate(EquityEdgeRequest $request, $id)
    {
        $edge = MercuryEquityEdge::find($id)->update($request->all());
        Session::flash('success', 'Record Successfully Updated!');
        return redirect()->route('admin.integrations.mercury');
    }

    /**
    * statusesView
    * @param $mercuryStatus,$status
    * @return view
    */
    private function statusesView($mercuryStatus,$status)
    {
        $saveStatuses = MercuryStatusRelation::all();
        $mercuryStatuses = $mercuryStatus->allStatuses();
        $statuses = $status->allStatuses();

        return view('admin::integrations.mercury_network.partials._statuses',
            compact(
                'mercuryStatuses',
                'statuses',
                'saveStatuses'
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
        MercuryStatusRelation::truncate();
        foreach($inputs as $mercuryId => $internalId) {
            if($internalId) {
                MercuryStatusRelation::create([
                    'mercury_status_id' => $mercuryId,
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
    private function appraisalTypesView ($mercuryApprType, $type, $mercuryApprTypeRelation)
    {
        $mercuryApprTypes = $mercuryApprType->allTypes();
        $internalPropertyTypes = PropertyType::all();
        $internalOccupancyStatuses = OccupancyStatus::all();
        $internalAddendas = Addenda::all();
        $internalApprTypes = $type->allTypes();
        $savedData = $mercuryApprTypeRelation->getSavedData();

        return view('admin::integrations.mercury_network.partials._appraisal_types',
            compact(
                'mercuryApprTypes',
                'internalPropertyTypes',
                'internalOccupancyStatuses',
                'internalAddendas',
                'internalApprTypes',
                'savedData'
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
        MercuryApprTypeRelation::truncate();

        foreach($apprTypes as $id => $typeId) {
            $addendas = isset($request->addendas[$id]) ? $request->addendas[$id] : null;
            if (
                    !empty($typeId) ||
                    !empty($request->prop_type[$id]) ||
                    !empty($request->occ_status[$id]) ||
                    !is_null($addendas)
                ) {
                MercuryApprTypeRelation::create([
                    'mercury_type_id' =>  $id,
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
    private function loanTypesView ($mercuryLoanType, $loanReason)
    {
        $savedTypes = MercuryLoanTypeRelation::all();
        $mercuryLoanTypes = $mercuryLoanType->allTypes();
        $internalReason = $loanReason->allReasons();
        $internalTypes = LoanType::all();

        return view('admin::integrations.mercury_network.partials._loan_types',
            compact(
                'savedTypes',
                'mercuryLoanTypes',
                'internalReason',
                'internalTypes'
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

        MercuryLoanTypeRelation::truncate();
        foreach($types as $id => $typeId) {
            if (!empty($typeId) || !empty($reason[$id])) {
                MercuryLoanTypeRelation::create([
                    'mercury_type_id' => $id,
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
    private function loanReasonView($mercuryLoanReason, $loanReason)
    {
        $savedLoanReasons = MercuryLoanReasonRelation::all();
        $mercuryLoanReason = $mercuryLoanReason->allReasons();
        $internalReason = $loanReason->allReasons();

        return view('admin::integrations.mercury_network.partials._loan_reasons',
            compact(
                'savedLoanReasons',
                'mercuryLoanReason',
                'internalReason'
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
        MercuryLoanReasonRelation::truncate();
        foreach($inputs as $mercuryId => $internalId) {
            if($internalId) {
                MercuryLoanReasonRelation::create([
                    'mercury_type_id' => $mercuryId,
                    'lni_type_id' => $internalId
                ]);
            }
        }
        Session::flash('success', 'Loan Reasons Successfully Updated!');
        return redirect()->back();
    }

   
}
