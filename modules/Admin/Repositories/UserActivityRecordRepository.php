<?php

namespace Modules\Admin\Repositories;


use App\Models\Users\ActivityRecord;

class UserActivityRecordRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getAllByUserId($userId)
    {
        return ActivityRecord::from('user_activity_record as b')
            ->selectRaw(
                "a.id, a.user_id, a.created_by, a.created_date, b.column_name, 
                 b.from_value, b.to_value, 
                 CONCAT(o.firstname,' ',o.lastname) as fullname, 
                 CONCAT(z.firstname,' ',z.lastname) as author_fullname"
            )->leftJoin('user_activity as a', 'a.id', '=', 'b.activity_id')
            ->leftJoin('user as u', 'a.user_id', '=', 'u.id')
            ->leftJoin('user_data as o', 'u.id', '=', 'o.user_id')
            ->leftJoin('user as ua', 'a.created_by', '=', 'ua.id')
            ->leftJoin('user_data as z', 'ua.id', '=', 'z.user_id')
            ->where('a.user_id', $userId)
            ->orderBy('a.id')->get();
    }
}
