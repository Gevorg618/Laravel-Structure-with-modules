<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\Appraisal\Order;
use App\Models\Management\Survey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Dashboard\Http\Requests\SurveysRequest;

class SurveysController extends DashboardBaseController
{
    
    /**
     * Prepare survey questions
     *
     * @param $survey_id
     * @param string $order_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prepare($survey_id, $order_id = '')
    {
        //lkog user for test
        Auth::loginUsingId(133039);
        
        $order = Order::where('id', $order_id)->first();
        $survey = (new Survey)->prepare($survey_id, $order_id);
        
        return view('dashboard::surveys.prepare', compact('survey', 'order'));
    }
    
    /**
     * Submit populated survey
     *
     * @param SurveysRequest $request
     * @param $survey_id
     * @param string $order_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function submit(SurveysRequest $request, $survey_id, $order_id = '')
    {
        $survey = Survey::findorFail($survey_id);
        $survey->submit($request, $order_id);
    
        Session::flash('success', 'Survey is submitted.');
        
        return view('dashboard::surveys.thankyou', compact('survey'));
    }
}
