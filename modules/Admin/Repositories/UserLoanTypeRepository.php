<?php

namespace Modules\Admin\Repositories;


use App\Models\UserLoanType;

class UserLoanTypeRepository
{
    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserSelectedLoanTypes($userId)
    {
        return UserLoanType::where('user_id', $userId)
            ->pluck('loan_type_id', 'loan_type_id');
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteAllByUser($userId)
    {
        return UserLoanType::where('user_id', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return UserLoanType::insert($data);
    }
}
