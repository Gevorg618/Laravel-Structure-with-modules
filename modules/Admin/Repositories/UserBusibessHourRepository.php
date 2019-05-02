<?php

namespace Modules\Admin\Repositories;


use App\Models\UserBusinessHour;

class UserBusibessHourRepository
{
    /**
     * @param $id
     * @return array
     */
    public function getSelectedBusinessHours($id)
    {
        $rows = [];
        $data = UserBusinessHour::where('user_id', $id)->get();

        if ($data) {
            foreach ($data as $row) {
                $rows[ $row->day ] = ['from' => $row->hour_from, 'to' => $row->hour_to];
            }
        }

        return $rows;
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function deleteByUser($id)
    {
        return UserBusinessHour::where('user_id', $id)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data = [])
    {
        return UserBusinessHour::insert($data);
    }
}