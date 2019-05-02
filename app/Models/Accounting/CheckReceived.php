<?php

namespace App\Models\Accounting;


class CheckReceived extends BaseModel
{
    protected $table = 'checks_received';

    public $timestamps = false;

    protected $fillable = [
        'orderid',
        'adminid',
        'checknum',
        'checkamount',
        'check_from',
        'dts',
        'date_recv',
    ];
}
