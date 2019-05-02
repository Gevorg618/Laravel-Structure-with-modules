<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Accounting\AccountingPayablePayment;

/**
 * Class AccountingRepository
 * @package Modules\Admin\Repositories
 */
class AccountingRepository
{
    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedPayablePayments()
    {
        return AccountingPayablePayment::with('user')->latest('id')->paginate(50);
    }
}