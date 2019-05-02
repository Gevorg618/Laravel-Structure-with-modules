<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Customizations\LoanReason;
use App\Models\Customizations\LoanType;
use App\Models\Appraisal\QC\DataQuestion;
use App\Models\Customizations\Type;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\QC\DataCollectionRequest;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Yajra\DataTables\DataTables;

class DataCollectionController extends Controller
{
    protected $typesRepository;

    /**
     * DataCollectionController constructor.
     * @param $typesRepository
     */
    public function __construct(TypesRepository $typesRepository)
    {
        $this->typesRepository = $typesRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::management.data_collection.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $collection = DataQuestion::with('user')->get();

            return DataTables::of($collection)
                ->editColumn('is_active', function ($r) {
                    return $r->is_active ? 'Yes' : 'No';
                })
                ->editColumn('is_required', function ($r) {
                    return $r->is_required ? 'Yes' : 'No';
                })
                ->editColumn('id', function ($r) {
                    return 'qc.data.' . $r->id;
                })
                ->editColumn('created_by', function ($r) {
                    return $r->user->fullname;
                })
                ->editColumn('created_date', function ($r) {
                    return date('m/d/Y G:i A', $r->created_date);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.data_collection.partials._options', [
                        'row' => $r
                    ]);
                })
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::management.data_collection.create', [
            'loanReasons' => LoanReason::pluck('descrip', 'id'),
            'loanTypes' => LoanType::pluck('descrip', 'id'),
            'appraisalTypes' => $this->typesRepository->getTypesForMultiSelect(),
            'clients' => Client::pluck('descrip', 'id'),
            'lenders' => UserGroupLender::pluck('lender', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(DataCollectionRequest $request)
    {
        $question = new DataQuestion();
        $question = $question->setDataToModel($request);
        $question->created_by = auth('admin')->id();
        try {
            \DB::beginTransaction();
            if ($question->save()) {
                $question->saveRelations($request->only([
                    'loan_reason',
                    'loan_type',
                    'appraisal_type',
                    'clients',
                    'lenders',
                ]));
                \DB::commit();
            }
            return redirect(route('admin.qc.collection.index'))
                ->with('success', 'Question has been successfully updated');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
        }
        return redirect(route('admin.qc.collection.index'))
            ->with('error', 'Could not update question');
    }

    /**
     * @param DataQuestion $row
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(DataQuestion $row)
    {
        return view('admin::management.data_collection.edit', [
            'row' => $row,
            'loanReasons' => LoanReason::pluck('descrip', 'id'),
            'loanTypes' => LoanType::pluck('descrip', 'id'),
            'appraisalTypes' => $this->typesRepository->getTypesForMultiSelect(),
            'clients' => Client::pluck('descrip', 'id'),
            'lenders' => UserGroupLender::pluck('lender', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param DataQuestion $question
     * @param  Request $request
     * @return Response
     */
    public function update(DataQuestion $question, DataCollectionRequest $request)
    {
        $question = $question->setDataToModel($request);
        try {
            \DB::beginTransaction();
            if ($question->save()) {
                $question->eraseRelations();
                $question->saveRelations($request->only([
                    'loan_reason',
                    'loan_type',
                    'appraisal_type',
                    'clients',
                    'lenders',
                ]));
                \DB::commit();
                return redirect(route('admin.qc.collection.index'))
                    ->with('success', 'Question has been successfully updated');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
        }
        return redirect(route('admin.qc.collection.index'))
            ->with('error', 'Could not update question');
    }

    /**
     * Remove the specified resource from storage.
     * @param DataQuestion $question
     * @return Response
     */
    public function destroy(DataQuestion $question)
    {
        $question->eraseRelations();
        $question->delete();
        return redirect(route('admin.qc.collection.index'))
            ->with('success', 'Question has been successfully deleted');
    }
}
