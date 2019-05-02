<?php

namespace Modules\Admin\Http\Controllers\AutoSelectPricing;

use App\Models\Geo\State;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\AutoSelect\AutoSelectAppraiserCreateRequest;
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Admin\Repositories\AutoSelectPricing\AppraiserFeePricingRepository;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Auth, Response, Exception;
use Modules\Admin\Helpers\Excel;

class AutoSelectAppraiserFeesController extends AdminBaseController
{

    protected $statesRepo;
    protected $typesRepo;
    protected $apprFeePricingRepository;
    protected $excel;

    /**
     * AutoSelectAppraiserFeesController constructor.
     */
    public function __construct()
    {
        $this->statesRepo = new StatesRepository();
        $this->typesRepo  = new TypesRepository();
        $this->apprFeePricingRepository = new AppraiserFeePricingRepository();
        $this->excel = new Excel();
    }

    /**
     * auto select appraiser fees index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $states = $this->statesRepo->getStates();
        return view('admin::auto_select_pricing.appraiser-fees.index', compact('states'));
    }

    /**
     * insert or update the appraiser pricing fees from csv file
     *
     * @param AutoSelectAppraiserCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AutoSelectAppraiserCreateRequest $request)
    {
        $states = $request->input('state');
        $file  = $request->file('fees');
        $checkState = $this->statesRepo->getMultipleStatesByAbbr($states);
        if ($checkState->count() != count($states)) {
            Session::flash('error', 'Sorry, that state is not valid.');

            return redirect()->back();
        }
        try {
            DB::beginTransaction();
            $data =   $this->excel->loadFile($file);
            if (isset($data['success']) && $data['success'] == '0') {
                Session::flash($data['type'], $data['message']);

                return redirect()->back();
            }
            if (!$data) {
                Session::flash('error', 'Sorry, There are not records to import.');

                return redirect()->back();
            }
            $result = $this->apprFeePricingRepository->validateCsvAndStore($states, $data);
            DB::commit();
            Session::flash($result['type'], $result['message']);

            return redirect()->back();
        } catch (Exception $exception) {
            DB::rollBack();
            Session::flash('error', $exception->getMessage());

            return redirect()->back();
        }
    }

    /**
     * download sample template
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadTemplate()
    {
        $heads = $this->apprFeePricingRepository->headsArray;
        $lines = $this->typesRepo->getTypesForSampleTemplate($heads);
        $template = 'template';
        $ext = 'csv';
        $result =  $this->excel->handleExport($lines, $template, $ext);
        if ($result['success'] == '0') {
            Session::flash($result['type'], $result['message']);

            return redirect()->back();
        }
    }

    /**
     * download template of the group
     *
     * @param $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadStateTemplate($state)
    {
        $checkState = $this->statesRepo->getStateByAbbr($state);
        if (!$checkState) {
            Session::flash('error', 'Sorry, that state is not valid.');

            return redirect()->back();
        }
        $types = $this->typesRepo->createTypesArray();
        $lines = $this->apprFeePricingRepository->createLinesForTemplate($types, $state);
        $template = $checkState->state;
        $ext = 'csv';
        $result =  $this->excel->handleExport($lines, $template, $ext);
        if ($result['success'] == '0') {
            Session::flash($result['type'], $result['message']);

            return redirect()->back();
        }
    }

    /**
     * show appraiser pricing form for single state
     *
     * @param $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($state)
    {
        $checkState = $this->statesRepo->getStateByAbbr($state);
        if (!$checkState) {
            Session::flash('error', 'Sorry, that state is not valid.');

            return redirect()->back();
        }
        $types           = $this->typesRepo->createTypesArray();
        $apprFeesPricing = $this->apprFeePricingRepository->geByOneState($state);

        return view('admin::auto_select_pricing.appraiser-fees.form', compact('checkState', 'types', 'apprFeesPricing'));
    }

    /**
     * update appraiser fee pricing
     *
     * @param $state
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($state, Request $request)
    {
        $appraiserFeePricing = $request->input('appr_fee_pricing');
        try {
            DB::beginTransaction();
            $this->apprFeePricingRepository->destroyByState($state);
            $this->apprFeePricingRepository->insertManyAppraiserFeePricing($state, $appraiserFeePricing);
            DB::commit();
            Session::flash('success', 'changes were made successfully.');

            return redirect()->route('admin.autoselect.appraiser.fees.index');
        } catch (Exception $exception) {
            DB::rollBack();
            Session::flash('error', $exception->getMessage());

            return redirect()->back();
        }
    }
}