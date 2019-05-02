<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Rule extends BaseModel
{
    const MATCH_TYPE_ANY = 'any';
    const MATCH_TYPE_ALL = 'all';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_rule';

    protected $fillable = [
        'title',
        'description',
        'match_type',
        'is_active',
        'created_date',
        'created_by'
    ];

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

    /**
     * Connection to tickets_rule_actions table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function action()
    {
        return $this->hasOne('App\Models\Ticket\RuleAction', 'rule_id');
    }

    /**
     * Connection to tickets_rule_condition table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions()
    {
        return $this->hasMany('App\Models\Ticket\RuleCondition', 'rule_id');
    }

    public static function getMatchTypes()
    {
        return [
            self::MATCH_TYPE_ANY => 'Any',
            self::MATCH_TYPE_ALL => 'All'
        ];
    }
}
