<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\AlternativeValuation\OrderLogType;

class AltOrderLogTypeRepository
{
    public function getIdByType($type)
    {
        $row = OrderLogType::where('code', $type)->first();
        if($row) {
            return $row->id;
        }
        return null;
    }
}