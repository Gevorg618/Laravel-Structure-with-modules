<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Participate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_participate';

    protected $fillable = ['ticket_id', 'user_id'];

    public $timestamps = false;

    /**
     * @param $query
     * @return mixed
     */
    public function scopeParticipant($query)
    {
        return $query->where('user_id', admin()->id);
    }
}
