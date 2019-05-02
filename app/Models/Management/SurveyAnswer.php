<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use App\Models\Tiger\Amc;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SurveyAnswer extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'survey_answer';
    
    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $report = [];
    
    /**
     * Fillable fields
     * @var array
     */
    public $fillable = [
        'question_id',
        'rel_id',
        'answer',
        'created_date',
        'created_by'
    ];
    
    /**
     * Connection to order
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Appraisal\Order', 'rel_id');
    }
    
    /**
     * Generate report to display on the page
     *
     * @param $survey_id
     * @return \Illuminate\Support\Collection
     */
    public function report($survey_id)
    {
        $query = DB::table('survey_answer AS sa');
        $query->selectRaw('DATE_FORMAT(FROM_UNIXTIME(sa.created_date), \'%m/%d/%Y %H:%i\') AS answered_date, sq.id, ao.id as order_id');
        $query->selectRaw('ao.borrower, CONCAT(ao.propaddress1, \' \' ,ao.propcity, \' \' ,ao.propstate) as property_address, ao.ordereddate, ao.date_delivered');
        $query->selectRaw('ao.assigned_by, ao.acceptedby, ao.amc_id');
        $query->join('survey_question AS sq', 'sa.question_id', '=', 'sq.id');
        $query->join('appr_order AS ao', 'sa.rel_id', '=', 'ao.id');
        $query->where('sq.survey_id', $survey_id);
        $query->orderBy('sa.created_date', 'DESC');
        
        $surveys = $query->get();

        foreach ($surveys AS $survey) {
            $this->report[$survey->order_id] = $survey;
        }
        
        $this->addEngagerUsers();
        $this->addAppraisalUsers();
        
        return collect($this->report);
    }
    
    /**
     * Add Engager users data
     */
    private function addEngagerUsers()
    {
        $users = $this->filterUsers('assigned_by');

        foreach ($this->report AS $report) {
            $report->engager = '';
            if (!empty($users[$report->assigned_by])) {
                $report->engager = $users[$report->assigned_by];
            }
        }
    }
    
    /**
     * Add Appraisal users
     */
    private function addAppraisalUsers()
    {
        $users = $this->filterUsers('acceptedby');
        $amcs = $this->filterAmc();
        
        foreach ($this->report AS $report) {
            $report->appraiser = '';
            if (!empty($users[$report->acceptedby])) {
                $report->appraiser = $users[$report->acceptedby];
            }
            elseif (!empty($amcs[$report->amc_id])) {
                $report->appraiser = $amcs[$report->amc_id];
            }
        }
    }
    
    /**
     * Get AMC vendor from Tiger database
     * @return array
     */
    private function filterAmc()
    {
        $amcs = Amc::whereIn('id', array_unique(array_pluck($this->report, 'amc_id')))
            ->get();

        $userData = [];
        foreach ($amcs AS $amc) {
            $userData[$amc->id] = trim(ucwords(strtolower($amc->title)));
        }
    
        return $userData;
    }
    
    /**
     * Filter users for report
     * @return array
     */
    private function filterUsers($filter)
    {
        $users = User::whereIn('id', array_unique(array_pluck($this->report, $filter)))
            ->with('userData')
            ->get();
        
        $userData = [];
        foreach ($users AS $user) {
            $userData[$user->id] = trim(ucwords(strtolower($user->userData->firstname . ' ' . $user->userData->lastname)));
        }
        
        return $userData;
    }
    
    /**
     * Generate CSV report for downloading
     * @param $request
     */
    public static function reportCSV($request)
    {
        $survey = Survey::where('id', $request->survey_id)
            ->with([
                'questions' => function($query){
                    $query->orderBy('id', 'ASC');
                },
                'answers' =>  function($query) use ($request) {
                    if ($request->type == 'answered') {
                        $query->whereRaw('survey_answer.created_date >= '.strtotime($request->date_start));
                        $query->whereRaw('survey_answer.created_date <= '.strtotime($request->date_end));
                        $query->orderBy('created_date', 'ASC');
                    }
                },
                'answers.order' =>  function($query) use($request){
                    if ($request->type == 'date_delivered') {
                        $query->whereRaw('appr_order.date_delivered >= \''.$request->date_start.'\'');
                        $query->whereRaw('appr_order.date_delivered <= \''.$request->date_end.'\'');
                    }
                }
            ])
            ->first();

        foreach($survey->questions as $question) {
            // Q title
            $questionTitles[$question->id] = $question->title;
        }
        
        $answers = [];
    
        foreach ($survey->answers AS $answer) {
            // Q -> Order -> Answer
            if ($request->type == 'date_delivered') {
                if (!empty($answer->order)) {
                    $answers[$answer->rel_id]['oid'] = $answer->rel_id;
                    $answers[$answer->rel_id][$answer->question_id] = preg_replace('/\,/', '', $answer->answer);
                }
            }
            else {
                $answers[$answer->rel_id]['oid'] = $answer->rel_id;
                $answers[$answer->rel_id][$answer->question_id] = preg_replace('/\,/', '', $answer->answer);
            }
        }

        Excel::create('Survey-Report-'.date('Y_m_d', strtotime($request->date_start)).'---'.date('Y_m_d', strtotime($request->date_end)), function($excel) use ($survey, $answers) {
        
            $excel->sheet('Sheetname', function($sheet) use ($survey, $answers) {
                // Sheet manipulation
                $sheet->loadView('admin::management.survey_answers.report', compact('survey', 'answers'));
            });
        })->export('csv');
    }
    
}
