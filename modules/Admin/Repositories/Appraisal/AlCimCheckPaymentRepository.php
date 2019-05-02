<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Accounting\AlCimCheckPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AlCimCheckPaymentRepository
{
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return AlCimCheckPayment::with([
            'order'
        ])->whereBetween('date_received', [
            strtotime($from . ' 00:00:00'),
            strtotime($to . ' 23:59:59')
        ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }
}