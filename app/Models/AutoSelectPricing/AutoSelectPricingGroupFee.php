<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\User;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;

class AutoSelectPricingGroupFee extends BaseModel
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
    protected $table = 'autoselect_pricing_group_fees';

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
        'group_id',
        'state',
        'appr_type',
        'amount',
        'fhaamount',
        'fee_type',
        'created_by',
        'created_date',
        'last_updated_by',
        'last_updated_date'
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
        return $this->belongsTo(User::class, 'last_updated_by')->with('userData');
    }

    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'group_id');
    }

    /**
     * get count by id 
     *
     * @return Integer
     */
    public function groupCount()
    {
        return $this->groupBy('group_id')->count();
    }
}
