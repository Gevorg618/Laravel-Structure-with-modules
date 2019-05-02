<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprQcAnswer;

class ApprQcAnswersRepository
{
    /**
     * @param $qcId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getWrongCorrections($qcId)
    {
        return ApprQcAnswer::from('appr_qc_answers as a')
            ->leftJoin('appr_qc_checklist as c', 'a.qc_question_id', '=', 'c.id')
            ->where('a.qc_id', $qcId)
            ->where('a.selection', 'Y')
            ->groupBy('a.qc_question_id')
            ->get();
    }
}