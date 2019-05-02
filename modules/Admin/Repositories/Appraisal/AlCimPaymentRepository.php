<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Accounting\AlCimPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AlCimPaymentRepository
{
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return AlCimPayment::with([
            'order'
        ])->where('is_success', 1)
            ->where('is_void', 0)
            ->whereBetween('created_date', [
                strtotime($from . ' 00:00:00'),
                strtotime($to . ' 23:59:59')
            ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }
}