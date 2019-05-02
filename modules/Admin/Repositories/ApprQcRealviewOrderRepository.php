<?php

namespace Modules\Admin\Repositories;


use App\Models\Appraisal\ApprQcRealviewOrder;

class ApprQcRealviewOrderRepository
{
    /**
     * @param $id
     * @param $revision
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getRealViewOrder($id, $revision)
    {
        return ApprQcRealviewOrder::where('order_id', $id)
            ->where('revision', $revision)
            ->latest('created_date')->get();
    }
}