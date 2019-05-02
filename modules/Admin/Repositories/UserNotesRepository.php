<?php

namespace Modules\Admin\Repositories;


use App\Models\UserNote;

class UserNotesRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserNotes($userId)
    {
        return UserNote::where('userid', $userId)
            ->orderBy('dts')->get();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data = [])
    {
        return UserNote::insert($data);
    }
}