<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\User;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;

class AutoSelectClientTurnTime extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'autoselect_client_turn_times';

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
        'client_id',
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
     * group is created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
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
