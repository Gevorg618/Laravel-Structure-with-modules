<?php

namespace Modules\Admin\Repositories\Accounting;

use App\Models\DocuVault\CheckPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DocuvaultCheckPaymentRepository
{
    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDailyBatchData($from, $to, $type)
    {
        return CheckPayment::with([
            'order'
        ])->whereBetween('date_received', [
                strtotime($from . ' 00:00:00'),
                strtotime($to . ' 23:59:59')
            ])->dailyBatchFilter($type)
            ->orderBy('created_date');
    }
}