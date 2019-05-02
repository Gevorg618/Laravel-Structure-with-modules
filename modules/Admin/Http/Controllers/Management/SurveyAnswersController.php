<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Management\Survey;
use App\Models\Management\SurveyAnswer;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\SurveyReportsRequest;
use Yajra\DataTables\Facades\Datatables;

class SurveyAnswersController extends AdminBaseController
{
    /**
     * Index page for Survey Report
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $surveys = Survey::prepareSelection();
        
        return view('admin::management.survey_answers.index', compact('surveys'));
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
    
            $surveys = Survey::with([
                'questions',
                'answers' => function($query){
                    $query->join('appr_order', 'survey_answer.rel_id', '=', 'appr_order.id');
                    $query->groupBy('rel_id');
                }
            ])->get();
          
            return Datatables::of($surveys)
                ->editColumn('type', function ($r) {
                    return ucfirst($r->type);
                })
                ->editColumn('is_active', function ($r) {
                    return ($r->is_active) ? 'Yes' : 'No';
                })
                ->editColumn('expires_date', function ($r) {
                    return date('m/d/Y', $r->expires_date);
                })
                ->addColumn('questions', function ($r) {
                    return count($r->questions);
                })
                ->addColumn('answers', function ($r) {
                    return count($r->answers);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.survey_answers.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    public function show($survey_id)
    {
        $survey = Survey::where('id', $survey_id)->first();

        $surveyAnswers = (new SurveyAnswer())->report($survey_id);
    
        return view('admin::management.survey_answers.show', compact(['survey', 'surveyAnswers']));
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function answersData(Request $request, $survey_id)
    {
        if ($request->ajax()) {
    
            $surveyAnswers = (new SurveyAnswer())->report($survey_id);
                        
            return Datatables::of($surveyAnswers)
                ->editColumn('date_order', function ($r) {
                    return $r->ordereddate? date('m/d/Y H:i', strtotime($r->ordereddate)) : '';
                })
                ->addColumn('date_delivered', function ($r) {
                    return $r->date_delivered ? date('m/d/Y H:i', strtotime($r->date_delivered)) : '';
                })
                ->addColumn('date_answered', function ($r) {
                    return $r->answered_date ;
                })
                ->make(true);
        }
    }
    
    /**
     * Generate CSV report for download
     *
     * @param SurveyReportsRequest $request
     */
    public function report(SurveyReportsRequest $request)
    {
        SurveyAnswer::reportCSV($request);
    }
}
