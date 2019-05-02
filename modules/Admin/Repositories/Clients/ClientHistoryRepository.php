<?php


namespace Admin\Repositories\Clients;


use App\Models\Clients\History;

class ClientHistoryRepository
{
    /**
     * Object of History class
     *
     * @var History
     */
    private $clientHistory;


    /**
     * ClientHistoryRepository constructor.
     */
    public function __construct()
    {
        $this->clientHistory = new History();
    }


    /**
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        return $this->clientHistory->create($params);
    }


    /**
     * @param $groupId
     * @return mixed
     */
    public function get($groupId)
    {
        return $this->clientHistory->where('group_id', $groupId)->orderBy('created_date', 'DESC')->get();
    }

}
