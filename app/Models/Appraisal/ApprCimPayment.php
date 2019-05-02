<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;
use App\Models\Users\User;

/**
 * Class ApprCimPayment
 * @package App\Models\Appraisal
 */
class ApprCimPayment extends BaseModel
{
    const CHARGE = 'CHARGE';
    const REFUND = 'REFUND';
    const VOID = 'VOID';
    
    protected $table = 'appr_cim_payments';

    protected $appends = ['batchAmount', 'gateway'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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

    /**
     * @return string
     */
    public function getGatewayAttribute()
    {
        return 'Authorize.net';
    }
}
