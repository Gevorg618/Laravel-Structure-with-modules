<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\Appraisal\Order;
use App\Models\BaseModel;
use App\Models\Users\User;

/**
 * Class MercuryCharge
 * @package App\Models\Integrations\MercuryNetwork
 */
class MercuryCharge extends BaseModel
{
    const CHARGE = 'charge';
    const REFUND = 'refund';
    const VOID = 'void';
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
        return $this->belongsTo(Order::class, 'lni_id')->with([
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
        if ($this->transaction_type == self::CHARGE) {
            return $this->amount;
        }
        return -$this->amount;
    }
}
