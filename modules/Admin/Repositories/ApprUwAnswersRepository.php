<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprUwAnswer;

class ApprUwAnswersRepository
{
    /**
     * @param $uwId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getWrongCorrections($uwId)
    {
        return ApprUwAnswer::from('appr_uw_answers as a')
            ->selectRaw('COUNT(a.id) as total')
            ->leftJoin('appr_uw_conditions as c', 'a.uw_condition_id', '=', 'c.id')
            ->where('a.uw_id', $uwId)
            ->groupBy('a.uw_condition_id')->get();
    }
}