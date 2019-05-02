<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;
use App\Models\Users\User;
use App\Models\Customizations\Type;

class AutoSelectTurnTime extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'autoselect_turn_times';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'type_id',
        'turn_time',
        'created_by',
        'created_date',
        'last_edited_by',
        'last_edited_date'
    ];

    /**
     * group is created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userData');
    }

    /**
     * group is created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function editedBy()
    {
        return $this->belongsTo(User::class, 'last_edited_by')->with('userData');
    }

     /**
     * group is types appriasal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function types()
    {
        return $this->belongsTo(Type::class, 'type_id')->select(['id','descrip','form']);
    }
}
