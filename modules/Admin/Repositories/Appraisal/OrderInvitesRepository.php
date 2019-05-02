<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\OrderInvite;

class OrderInvitesRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getOrderAppraiserAssignmentInvitesByApprIdAdmin($id)
    {
        return OrderInvite::where('appr_id', $id)
            ->latest('created_date')->get();
    }

    /**
     * @param $userId
     * @return array
     */
    public function getAppraiserInviteCounts($userId)
    {
        $totalCount = OrderInvite::where('appr_id', $userId)->count();
        $acceptedCount = OrderInvite::where('appr_id', $userId)
            ->where('is_accepted', 1)->count();
        $declinedCount = OrderInvite::where('appr_id', $userId)
            ->where('is_rejected', 1)->count();

        return [
            'total' => $totalCount,
            'accepted' => $acceptedCount,
            'declined' => $declinedCount,
        ];
    }
}
