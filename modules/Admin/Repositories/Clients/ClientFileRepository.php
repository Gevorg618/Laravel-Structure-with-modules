<?php

namespace Admin\Repositories\Clients;

use App\Models\Clients\UserGroupFile;

class ClientFileRepository
{
    /**
     * Object of UserGroupFile class
     */
    private $model;


    /**
     * Object of ClientLogRepository class
     */
    private $clientLogRepository;


    /**
     * ClientFileRepository constructor.
     */
    public function __construct()
    {
        $this->model = new UserGroupFile();
        $this->clientLogRepository = new ClientLogRepository();
    }


    /**
     * Create New UserGroupFile.
     * @param  $params
     * @return bool
     */
    public function store($params)
    {
        return $this->model->create($params);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }


    /**
     * @param $groupId
     */
    public function logCreateByFile($groupId)
    {
        $this->model->creating(function ($clientFile) use ($groupId) {
            $dirty = $clientFile->getDirty();
            $params =
                [
                    'group_id' =>$groupId,
                    'created_date' => time(),
                    'created_by' => admin()->id,
                    'note' => '<span class="log_added">User Uploaded File  '.$dirty['filename'].'</span>',
                ];
            $this->clientLogRepository->store($params);
        });

        $fileData = $this->find($groupId);
        $this->model->deleting(function  ($fileData) {
            $params =
                [
                    'group_id' =>$fileData->group_id,
                    'created_date' => time(),
                    'created_by' => admin()->id,
                    'note' => '<span class="log_removed">User Deleted File '.$fileData->docname.'</span>',
                ];
            $this->clientLogRepository->store($params);
        });
    }


}
