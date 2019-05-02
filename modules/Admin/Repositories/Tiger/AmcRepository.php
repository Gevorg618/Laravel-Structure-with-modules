<?php

namespace Modules\Admin\Repositories\Tiger;
use App\Models\Tiger\Amc;

class AmcRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getForDropdown()
    {
        return Amc::where('is_active', 1)->pluck('title', 'id');
    }
}