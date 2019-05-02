<?php

namespace Modules\Admin\Repositories;


use App\Models\UserDataAppraiserSplit;
use Illuminate\Support\Collection;

class UserDataAppraiserSplitRepository
{
    /**
     * @param $userId
     * @return Collection
     */
    public function getAppraiserSplitValuesById($userId)
    {
        return UserDataAppraiserSplit::select(['apprid', 'fha', 'conv'])
            ->where('userid', $userId)->get()->keyBy('apprid');
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteByUser($userId)
    {
        return UserDataAppraiserSplit::where('userid', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return UserDataAppraiserSplit::insert($data);
    }
}