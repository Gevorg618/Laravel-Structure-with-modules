<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Viewed extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_viewed';
    protected $fillable = [
        'ticket_id',
        'user_id',
        'created_date',
    ];

    public $timestamps = false;
}
