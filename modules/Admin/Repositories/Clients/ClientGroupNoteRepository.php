<?php


namespace Admin\Repositories\Clients;


use App\Models\Clients\UserGroupNote;

class ClientGroupNoteRepository
{
    /**
     * Object of UserGroupNote class
     */
    private $model;


    /**
     * ClientGroupNoteRepository constructor.
     */
    public function __construct()
    {
        $this->model = new UserGroupNote();

    }


    /**
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        return $this->model->create($params);

    }



    /**
     * @param $groupId
     * @return mixed
     */
    public function get($groupId)
    {
        return $this->model->where('groupid', $groupId)->orderBy('dts', 'asc')->get();
    }
}
