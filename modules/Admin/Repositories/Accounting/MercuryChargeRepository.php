<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Integrations\MercuryNetwork\MercuryCharge;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MercuryChargeRepository
{
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return MercuryCharge::with([
            'order'
        ])->where('is_success', 1)
            ->whereBetween('created_date', [
            strtotime($from . ' 00:00:00'),
            strtotime($to . ' 23:59:59')
        ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }
}