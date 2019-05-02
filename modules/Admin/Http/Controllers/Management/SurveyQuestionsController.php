<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Management\Survey;
use App\Models\Management\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\SurveyQuestionsRequest;
use Yajra\DataTables\Facades\Datatables;

class SurveyQuestionsController extends AdminBaseController
{
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
    
            if (!empty($request->id)) {
                $questions = SurveyQuestion::with(['survey', 'user', 'user.userData'])
                    ->where('survey_id', $request->id)
                    ->get();
            } else {
                $questions = SurveyQuestion::with(['survey', 'user', 'user.userData'])
                    ->get();
            }
            
            return Datatables::of($questions)
                ->editColumn('created_by', function ($r) {
                    return isset($r->user) ? $r->user->fullname : '-';
                })
                ->editColumn('is_active', function ($r) {
                    return ($r->is_active) ? 'Yes' : 'No';
                })
                ->editColumn('created_date', function ($r) {
                    return date('m/d/Y H:i', $r->created_date);
                })
                ->editColumn('is_required', function ($r) {
                    return ($r->is_required) ? 'Yes' : 'No';
                })
                ->editColumn('type', function ($r) {
                    return ucfirst($r->type);
                })
                ->editColumn('survey_id', function ($r) {
                    return (!empty($r->survey->title) ? $r->survey->title : 'N/A');
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.survey_questions.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    /**
     * Create new property type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($survey = '')
    {
        //get all surveys
        $surveys = Survey::prepareSelection();
        return view('admin::management.survey_questions.create', compact(['surveys', 'survey']));
    }
    
    /**
     * Store new property type
     *
     * @param SurveysRequest $request
     * @param Survey $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SurveyQuestionsRequest $request, SurveyQuestion $question)
    {
        $question->store($request);
        Session::flash('success', 'Survey Question Successfully Created.');
    
        return redirect()->route('admin.management.surveys.show.questions', ['id' => $request->survey_id]);
    }
    
    /**
     * Edit property type details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //get all user types
        $surveys = Survey::prepareSelection();
        $question = SurveyQuestion::findOrFail($id);
        
        return view('admin::management.survey_questions.edit', compact(['surveys','question']));
    }
    
    /**
     * Update Property type details
     *
     * @param SurveysRequest $request
     * @param Survey $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SurveyQuestionsRequest $request, SurveyQuestion $question)
    {
        $question->store($request);
        Session::flash('success', 'Survey Question Successfully Updated.');
    
        return redirect()->route('admin.management.surveys.show.questions', ['id' => $request->survey_id]);
    }
    
    /**
     * Delete property type - soft delete
     *
     * @param Survey $survey
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(SurveyQuestion $question)
    {
        Session::flash('success', 'Survey Question is deleted.');
        $question->delete();
    
        return redirect()->route('admin.management.surveys.show.questions', ['id' => $question->survey_id]);
    }
}
