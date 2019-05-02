<?php

namespace App\Models\Management;

use App\Models\Appraisal\Order;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Survey extends BaseModel
{
    use SoftDeletes;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'survey';
    
    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'is_active',
        'expires_date'
    ];
    
    public $survey;
    
    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Connect Survey with questions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        
        return $this->hasMany('App\Models\Management\SurveyQuestion');
    }
    
    /**
     * Connect user who created survey with users table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Users\User','created_by');
    }
    
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types()
    {
        return $this->belongsToMany('App\Models\Management\UserType', 'survey_user_type', 'survey_id', 'user_type');
    }
    
    /**
     * Connection to survey answers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasManyThrough('App\Models\Management\SurveyAnswer', 'App\Models\Management\SurveyQuestion', 'survey_id', 'question_id', 'id');
    }
    
    /**
     * Set expires date to timestamp
     * @param $value
     */
    public function setExpiresDateAttribute($value)
    {
        $this->attributes['expires_date'] = strtotime($value);
    }
    
    /**
     * Before save attributes
     */
    public function beforeSave()
    {
        if(!$this->id)
        {
            $this->created_by = admin()->id;
            $this->created_date = time();
        }
        
        return parent::beforeSave();
    }
    
    public function scopePrepareSelection($query)
    {
        $query->orderBy('title', 'ASC');
        $surveys = $query->get();
        
        $selection = [];
        foreach ($surveys AS $survey) {
            $selection[$survey->id] = $survey->title;
        }
        
        return $selection;
    }
    
    /**
     * Get connected user types
     * @return array
     */
    public function connectedUserTypes()
    {
        return array_pluck($this->types()->get(), 'id');
    }
    
    /**
     * Store new Survey
     *
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $survey = Survey::findOrNew($request->id);
        $survey->fill($request->all());
        
        $survey->save();
    
        if($request->user_type) {
            $survey->types()->sync($request->user_type);
        }

        
        return true;
    }
    
    public function prepare($survey_id, $order_id = '')
    {
        $this->survey = Survey::where('id', $survey_id)
            ->where('is_active', 1)
            ->with([
                'questions' => function($q){
                    $q->where('is_active', 1);
                },
                'answers' => function($q) use ($order_id){
                    $q->where('rel_id', (($order_id) ? $order_id : Auth::user()->id));
                }
            ])
            ->first();
        
        $this->prepareAllowed($order_id);
        $this->isAnswered($order_id);
        $this->orderOrUserTypeExist($order_id);
        
        return $this->survey;
    }
    
    /**
     * Check if survey is active, if it has active questions
     * or does survey even exists
     *
     * @return bool
     */
    private function prepareAllowed($order_id)
    {
        if (!empty($this->survey)) {
            if ($this->survey->expires_date <= time()) {
                $this->survey->error = 'Sorry, that survey has expired.';
            }
        
            if (!$this->survey->questions()->count()) {
                $this->survey->error = 'Sorry, that survey has no questions.';
            }
            
            if (!$order_id && $this->survey->type == 'order') {
                $this->survey->error = 'Sorry, wrong survey type.';
            }
        }
        else {
            $this->survey->error = 'Sorry, that survey was not found.';
        }
    
        return true;
    }
    
    /**
     * Check if survey is already answered for order
     * or by logged user
     *
     * @param $order_id
     * @return bool
     */
    private function isAnswered($order_id)
    {
        if (!empty($order_id)) {
            if ($this->survey->answers()->where('rel_id', $order_id)->count()) {
                $this->survey->error = 'Sorry, the survey was already completed for this order.';

            }
        }
        else {
            if ($this->survey->answers()->where('rel_id', Auth::user()->id)->count()) {
                $this->survey->error = 'Sorry, the survey was already completed for this user.';
            }
    
        }
    }
    
    /**
     * Check if order for survey exists
     * OR if we don't have order check does logged
     * user type is same as survey user type
     *
     * @param $order_id
     * @return bool
     */
    private function orderOrUserTypeExist($order_id)
    {
        if (!empty($order_id)) {
            if (!Order::where('id', $order_id)->first()) {
                $this->survey->error = 'Sorry, that order was not found.';
            }
        }
        else {
            if (!in_array(Auth::user()->userType->id, $this->survey->connectedUserTypes())) {
                $this->survey->error = 'Sorry, you are not allowed to view this survey.';
            }
        }
    }
    
    /**
     * Submit survey
     *
     * @param $request
     * @param $order_id
     * @return bool
     */
    public function submit($request, $order_id)
    {
        $answers = $request->all();
    
        foreach ($answers AS $id => $val) {
            if (preg_match('/question-/', $id)) {
                SurveyAnswer::create([
                    'question_id'  => preg_replace('/question-/', '', $id),
                    'rel_id'       => (!empty($order_id) ? $order_id : Auth::user()->id),
                    'answer'       => $val,
                    'created_date' => time(),
                    'created_by'   => Auth::user()->id
                ]);
            }
        }
        
        return true;
    }
    
    
}
