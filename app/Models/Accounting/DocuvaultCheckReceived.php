<?php

namespace App\Models\Accounting;


class DocuvaultCheckReceived extends BaseModel
{
    protected $table = 'docuvault_checks_received';

    protected $fillable = [
        'orderid',
        'adminid',
        'checknum',
        'checkamount',
        'check_from',
        'dts',
        'date_recv',
    ];

    public $timestamps = false;
}