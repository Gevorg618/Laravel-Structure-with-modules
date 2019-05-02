<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class StatusRel extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_status_rel';

    public $timestamps = false;

    /**
     * Connection to tickets_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Ticket\Status', 'status_id');
    }

    /**
     * @param $query
     * @param bool|array $statuses
     * @return mixed
     */
    public function scopeOfStatuses($query, $statuses = false)
    {
        if ($statuses) {
            return $query->whereIn('status_id', $statuses);
        }
    }
}
