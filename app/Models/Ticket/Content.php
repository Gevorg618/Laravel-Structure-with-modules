<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Content extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_content';
    protected $fillable = [
        'ticket_id',
        'type',
        'content'
    ];

    public $timestamps = false;
}
