<?php

namespace App\Models\Management;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends BaseModel
{
    use SoftDeletes;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'survey_question';
    
    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'survey_id',
        'is_active',
        'is_required',
        'type',
        'rating_items',
        'description',
        'extra',
    ];
    
    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Conneciton to Survey table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey()
    {
        return $this->belongsTo('App\Models\Management\Survey', 'survey_id');
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
     * Connection to survey answers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Management\SurveyAnswer');
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
    
    /**
     * Save question survey
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $question = SurveyQuestion::findOrNew($request->id);
        $question->fill($request->all());
        $question->save();
        
        return true;
    }
    
    
}
