<?php

namespace Modules\Admin\Repositories;



use App\Models\Clients\Client;

class UserGroupsRepository
{
    /**
     * @param $groupId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getGroupData($groupId)
    {
        return Client::find($groupId);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateById($id, $data = [])
    {
        return Client::find($id)->update($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateBySalesId($id, $data = [])
    {
        return Client::where('salesid', $id)->update($data);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateBySalesId2($id, $data = [])
    {
        return Client::where('salesid2', $id)->update($data);
    }
}