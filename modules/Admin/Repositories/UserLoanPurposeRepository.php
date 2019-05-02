<?php

namespace Modules\Admin\Repositories;


use App\Models\UserLoanPurpose;

class UserLoanPurposeRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedLoanPurposes($userId)
    {
        return UserLoanPurpose::where('user_id', $userId)
            ->pluck('loan_purpose_id', 'loan_purpose_id');
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteAllByUser($userId)
    {
        return UserLoanPurpose::where('user_id', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return UserLoanPurpose::insert($data);
    }
}