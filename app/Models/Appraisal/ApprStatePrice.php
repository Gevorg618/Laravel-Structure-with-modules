<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;
use App\Models\Clients\Client;

/**
 * Class ApprFDPayment
 * @package App\Models\Appraisal
 */
class ApprStatePrice extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'appr_state_price';

    /**
     * Connection to order_documents_appr_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function groupData()
    {
        return $this->belongsTo(Client::class, 'groupid')->withCount(['setPricingVersions', 'emptyPricingVersions', 'apprStatePrice']);
    }
}
