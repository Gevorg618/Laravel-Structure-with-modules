<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\OrderDelayDate;

class OrderDelayDatesRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOrderDelayDates($id)
    {
        return OrderDelayDate::where('order_id', $id)
            ->where('start_date', '>', 0)
            ->where('end_date', '>', 0)->get();
    }
}