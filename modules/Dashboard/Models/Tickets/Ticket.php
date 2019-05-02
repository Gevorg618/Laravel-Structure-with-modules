<?php

namespace Dashboard\Models\Tickets;

use Route;
use App\Scopes\Tickets\AuthorScope;
use App\Models\Ticket\Ticket as BaseTicket;

class Ticket extends BaseTicket
{
    protected $dates = ['created_date'];

    protected $casts = [
      'created_date' => 'datetime'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AuthorScope);
    }
}