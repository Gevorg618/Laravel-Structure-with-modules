<?php

namespace Modules\Admin\Repositories;


use App\Models\ApprReference;

class ApprReferenceRepository
{
    /**
     * @param $userId
     * @return array
     */
    public function getUserReferrences($userId)
    {
        $items = [];
        $rows = ApprReference::where('user_id', $userId)->get();
        if($rows) {
            $i = 1;
            foreach($rows as $row) {
                $items[$i] = array(
                    'firstname' => $row->firstname,
                    'lastname' => $row->lastname,
                    'company' => $row->company,
                    'phone' => $row->phone,
                );
                $i++;
            }
        }

        return $items;
    }

    /**
     * @param $userId
     * @return bool|null
     */
    public function deleteByUser($userId)
    {
        return ApprReference::where('user_id', $userId)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertByArray($data = [])
    {
        return ApprReference::insert($data);
    }
}