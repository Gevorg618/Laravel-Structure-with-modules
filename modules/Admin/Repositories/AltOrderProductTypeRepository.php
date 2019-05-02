<?php

namespace Modules\Admin\Repositories;


use App\Models\AlternativeValuation\OrderProductType;

class AltOrderProductTypeRepository
{
    /**
     * @param $code
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getByCode($code)
    {
        return OrderProductType::where('code', $code)->first();
    }
}
