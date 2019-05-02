<?php

namespace Modules\Admin\Repositories;


use App\Models\UserLanguage;

class UserLanguageRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedLanguages($userId) {
        return UserLanguage::where('user_id', $userId)->pluck('language_id', 'language_id');
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteAllByUser($userId)
    {
        return UserLanguage::where('user_id', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return UserLanguage::insert($data);
    }
}