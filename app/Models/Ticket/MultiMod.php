<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class MultiMod extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_multi_mod';

    protected $fillable = [
        'title',
        'description',
        'is_active',
        'public_comment',
        'reply',
        'reply_all',
        'close_or_open',
        'assign_to',
        'set_status',
        'set_category',
        'set_priority',
        'assign_order',
        'comment',
        'add_participants',
    ];

    public $timestamps = false;

    /**
     * Connection to tickets_category table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Ticket\Category', 'set_category');
    }

    /**
     * Connection to tickets_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Ticket\Status', 'set_status');
    }

    /**
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return $this->status ? $this->status->name : config('constants.not_available');
    }
}
