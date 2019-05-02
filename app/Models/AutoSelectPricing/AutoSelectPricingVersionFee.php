<?php

namespace App\Models\AutoSelectPricing;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\User;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;
use App\Models\Appraisal\StatePricingVersion;

class AutoSelectPricingVersionFee extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'autoselect_pricing_version_fees';

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
        'pricing_version_id',
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
     * versionis created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userData');
    }

    /**
     * version  is created by user
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
    public function pricingVersions()
    {
        return $this->belongsTo(StatePricingVersion::class, 'pricing_version_id');
    }

    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'pricing_version_id');
    }
}
