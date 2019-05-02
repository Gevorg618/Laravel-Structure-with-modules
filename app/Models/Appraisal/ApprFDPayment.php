<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;
use App\Models\Users\User;

/**
 * Class ApprFDPayment
 * @package App\Models\Appraisal
 */
class ApprFDPayment extends BaseModel
{
    const CHARGE = 'CHARGE';
    const REFUND = 'REFUND';
    const VOID = 'VOID';
    /**
     * @var string
     */
    protected $table = 'appr_fd_payments';

    protected $appends = ['batchAmount', 'gateway'];

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

    /**
     * @return string
     */
    public function getGatewayAttribute()
    {
        return 'First Data';
    }

    /**
     * @param $query
     * @param $dateFrom
     * @param $dateTo
     * @param $dateType
     * @param string $paymentPrefix
     * @param string $orderPrefix
     * @return mixed
     */
    public function scopeGetDateCondition($query, $dateFrom, $dateTo, $dateType, $paymentPrefix = 'appr_fd_payments', $orderPrefix = 'appr_order')
    {
        if ($dateType == 'fd.created_date') {
            return $query->whereBetween($paymentPrefix . '.created_date', [
                strtotime($dateFrom),
                strtotime($dateTo),
            ]);
        }
        if ($orderPrefix != 'appr_order') {
            return $query->whereBetween($orderPrefix . '.' .$dateType, [$dateFrom, $dateTo]);
        }
        return $query->whereHas('order', function ($q) use ($dateFrom, $dateTo, $dateType) {
            return $q->whereBetween($dateType, [$dateFrom, $dateTo]);
        });
    }
}
