<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

class ApprAccountingAdmin extends BaseModel
{
    const ADD = 'add';
    const SUBTRACT = 'subrtact';

    public $timestamps = false;

    protected $table = 'appr_accounting_admin';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->with([
            'client',
            'groupData',
            'adminTeamClient',
        ]);
    }

    /**
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeDailyBatchFilter($query, $type)
    {
        if ($type == 'charges') {
            return $query->where('amount_type', self::ADD);
        }
        if ($type == 'refunds') {
            return $query->where('amount_type', self::SUBTRACT);
        }
        return $query;
    }

    /**
     * @return mixed
     */
    public function getBatchAmountAttribute()
    {
        if ($this->amount_type == self::SUBTRACT) {
            return -$this->amount;
        }
        return $this->amount;
    }
}
