<?php

namespace App\Models\Appraisal;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\AutoSelectPricing\{AutoSelectPricingVersionFee, ApprStatePricingVersion};
use App\Models\Clients\Client;
use App\Models\Appraisal\{LoanReason, Addenda};

class StatePricingVersion extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'appr_state_price_version';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'created_date',
        'pos',
    ];

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function autoSelectPricingVersionFees()
    {
        return $this->hasMany(AutoSelectPricingVersionFee::class, 'pricing_version_id')->with(['createdBy', 'editedBy']);
    }

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public static function getAll()
    {
        return self::get();
    }

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function clients()
    {
        return $this->hasMany(Client::class, 'pricing_version');
    }

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function apprPricingVersions()
    {
        return $this->hasMany(ApprStatePricingVersion::class, 'version_id');
    }

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function setPricingVersions()
    {
        return $this->apprPricingVersions()->where('amount', '>', 0)->where('fha_amount', '>', 0);
    }

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function emptyPricingVersions()
    {
        return $this->apprPricingVersions()->where('amount', '<=', 0)->where('fha_amount', '<=', 0);
    }

    /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function loanReasons()
    {
        return $this->belongsToMany(LoanReason::class, 'appr_state_price_version_loan_reason', 'version_id', 'loan_id');
    }

     /**
    * 
    * @return \Illuminate\Database\Eloquent\Relations\hasMany
    */
    public function addendas()
    {
        return $this->belongsToMany(Addenda::class, 'appr_state_pricing_addenda', 'pricing_version_id', 'addenda_id')->withPivot('amount');
    }

}
