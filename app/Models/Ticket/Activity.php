<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Activity extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_activity';

    public $timestamps = false;

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'created_by');
    }
}
