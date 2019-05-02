<?php
namespace Admin\Repositories\Clients;


use App\Models\Clients\Log;

class ClientLogRepository
{
    /**
     * Object of Log class
     *
     * @var Log
     */
    private $clientLog;


    /**
     * ClientLogRepository constructor.
     */
    public function __construct()
    {
        $this->clientLog = new Log();
    }


    /**
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        return $this->clientLog->create($params);
    }


    /**
     * @param $groupId
     * @return mixed
     */
    public function get($groupId)
    {
        return $this->clientLog->where('group_id', $groupId)->orderBy('created_date', 'DESC')->get();
    }
}
