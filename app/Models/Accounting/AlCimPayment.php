<?php

namespace App\Models\Accounting;

use App\Models\AlternativeValuation\Order;
use App\Models\Users\User;
use App\Models\BaseModel;

/**
 * Class AlCimPayment
 * @package App\Models
 */
class AlCimPayment extends BaseModel
{
    const CHARGE = 'CHARGE';
    const REFUND = 'REFUND';
    const VOID = 'VOID';
    /**
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo(User::class)->with('userData');
    }

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
            return $query->where('ref_type', self::CHARGE);
        }
        if ($type == 'refunds') {
            return $query->whereIn('ref_type', [
                self::REFUND,
                self::VOID
            ]);
        }
        return $query;
    }

    /**
     * @return mixed
     */
    public function getBatchAmountAttribute()
    {
        if ($this->ref_type == self::CHARGE) {
            return $this->amount;
        }
        return -$this->amount;
    }
}
