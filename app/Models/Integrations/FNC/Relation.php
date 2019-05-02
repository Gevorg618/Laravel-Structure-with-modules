<?php
    
namespace App\Models\Integrations\FNC;

use App\Models\BaseModel;

class Relation extends BaseModel
{
    protected $table = 'fnc_order_relation';
    protected $fillable = [
        'lni_oid',
        'fnc_oid',
        'port_id',
        'order_id',
        'uid',
    ];

    public $timestamps = false;
}
