<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Management\Survey;
use App\Models\Management\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\SurveysRequest;
use Yajra\DataTables\Facades\Datatables;

class SurveysController extends AdminBaseController
{
    
    /**
     * Index page for Surveys
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::management.surveys.index');
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            
            $surveys = Survey::with([
                'user',
                'user.userData',
                'questions'
            ])->get();
                
            return Datatables::of($surveys)
                ->editColumn('created_by', function ($r) {
                    return isset($r->user) ? $r->user->fullname : '-';
                })
                ->editColumn('is_active', function ($r) {
                    return ($r->is_active) ? 'Yes' : 'No';
                })
                ->editColumn('created_date', function ($r) {
                    return date('m/d/Y H:i', $r->created_date);
                })
                ->editColumn('expires_date', function ($r) {
                    return date('m/d/Y', $r->expires_date);
                })
                ->addColumn('questions', function ($r) {
                    return count($r->questions);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.surveys.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    /**
     * Create new property type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //get all user types
        $userTypes = UserType::all();

        return view('admin::management.surveys.create', compact('userTypes'));
    }
    
    /**
     * Store new property type
     *
     * @param SurveysRequest $request
     * @param Survey $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SurveysRequest $request, Survey $survey)
    {
        $survey->store($request);
        Session::flash('success', 'Survey Successfully Created.');
        
        return redirect()->route('admin.management.surveys.index');
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
        $userTypes = UserType::all();
        $survey = Survey::findOrFail($id);
        $survey->connected_user_types = $survey->connectedUserTypes();

        return view('admin::management.surveys.edit', compact(['userTypes','survey']));
    }
    
    /**
     * Update Property type details
     *
     * @param SurveysRequest $request
     * @param Survey $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SurveysRequest $request, Survey $survey)
    {
        $survey->store($request);
        Session::flash('success', 'Survey Successfully Updated.');
        
        return redirect()->route('admin.management.surveys.index');
    }
    
    /**
     * Delete property type - soft delete
     *
     * @param Survey $survey
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Survey $survey)
    {
        Session::flash('success', 'Survey is deleted.');
        $survey->delete();
        
        return redirect()->route('admin.management.surveys.index');
    }
    
    /**
     * Get questions for specific survey
     *
     * @param Survey $survey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questions(Survey $survey)
    {
        return view('admin::management.surveys.questions', compact('survey'));
    }
}
