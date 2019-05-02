<?php
    
namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;

class OrderRelation extends BaseModel
{
    protected $table = 'mercury_order_relation';
    protected $fillable = [
        'mercury_oid',
        'lni_oid'
    ];

    public $timestamps = false;
}
