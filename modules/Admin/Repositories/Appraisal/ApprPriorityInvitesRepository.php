<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\ApprPriorityInvite;

class ApprPriorityInvitesRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getActivePriorityInviteByUserId($id)
    {
        return ApprPriorityInvite::where('appr_id', $id)
            ->where('is_active', 1)->first();
    }
}