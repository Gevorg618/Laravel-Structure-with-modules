<?php

namespace App\Models\Appraisal\QC;

use App\Models\BaseModel;

class DataAnswer extends BaseModel
{
    protected $table = 'appr_qc_data_collection_answer';

    public $timestamps = false;

    /**
     * Connection to appr_qc_data_collection_question table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo('App\Models\Appraisal\QC\DataQuestion', 'question_id');
    }

    /**
     * @return mixed|string
     */
    public function getFormatValueAttribute()
    {
        if ($this->question) {
            switch ($this->question->format) {
                case 'number':
                    return number_format((integer)$this->value);

                case 'currency':
                    $value = floatval(str_replace(',', '', $this->value));
                    if (function_exists('money_format')) {
                        return money_format('%.2n', $value);
                    } else {
                        return number_format($value, 2);
                    }
            }
        }

        return $this->value;
    }
}