<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class RuleAction extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_rule_actions';

    protected $fillable = [
        'rule_id',
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
        'multi_mod',
        'add_participants'
    ];

    public $timestamps = false;

    /**
     * Connection to tickets_multi_mod table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moderation()
    {
        return $this->belongsTo('App\Models\Ticket\MultiMod', 'multi_mod');
    }
}
