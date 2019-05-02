<?php

namespace Modules\Admin\Repositories\Accounting;

use App\Models\Accounting\VendorTaxChange;

/**
 * Class VendorRepository
 * @package Modules\Admin\Repositories
 */
class VendorRepository
{
    /**
     * @param array $userIds
     * @param array $userEins
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getTaxRows($userIds = [], $userEins = [])
    {
        return VendorTaxChange::whereIn('user_id', $userIds)
            ->where('ein', '<>', '')
            ->whereNotIn('ein', $userEins)
            ->groupBy([
                \DB::raw("
                    CONCAT(ein,'',tax_class,'',company)
                ")
            ])
            ->orderBy('created_date')->get();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data = [])
    {
        return VendorTaxChange::insert($data);
    }
}