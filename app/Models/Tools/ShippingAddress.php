<?php

namespace App\Models\Tools;

use App\Models\BaseModel;

class ShippingAddress extends BaseModel
{
    protected $table = 'shipping_address';

    protected $fillable = [
        'shippment_id',
        'address_type',
        'shipping_address_id',
        'name',
        'company',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'email',
    ];

}
