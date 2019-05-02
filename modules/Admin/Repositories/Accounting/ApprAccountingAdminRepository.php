<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Appraisal\ApprAccountingAdmin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ApprAccountingAdminRepository
{
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return ApprAccountingAdmin::with([
            'order'
        ])->whereBetween('created_date', [
            strtotime($from . ' 00:00:00'),
            strtotime($to . ' 23:59:59')
        ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }
}