<?php

namespace App\Models\DocuVault;

use App\Models\BaseModel;
use App\Models\DocuVault\Order;

class CheckPayment extends BaseModel
{
    const CHARGE = 'CHARGE';
    const REFUND = 'REFUND';
    const VOID = 'VOID';

    protected $table = 'appr_docuvault_cim_check_payments';

    public $timestamps = false;

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
        if ($this->ref_type == self::REFUND) {
            return -$this->amount;
        }
        return $this->amount;
    }
}
