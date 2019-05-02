<?php

namespace Modules\Admin\Repositories\Clients;

use App\Models\Clients\Client;

class ClientRepository
{
    /**
     * Object of Client class
     *
     * @var $client
     */
    private $client;

    /**
     * ClientRepository constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * get all clients
     *
     * @return collection
     */
    public function clients()
    {
        return $this->client->select('id', 'descrip')->get();
    }

    /**
     * update Client
     * @param $inputs, $column
     * @return array
     */
    public function updateClient($inputs, $column)
    {
        $count = [
            'added' => 0,
            'removed' => 0
        ];
        $clients = isset($inputs['clients']) ? $inputs['clients'] : [];
        $add = isset($inputs['add']) ? $inputs['add'] : [];
        $remove = isset($inputs['remove']) ? $inputs['remove'] : [];

        if(!$add && !$remove) {
            return null;
        }

        if($add) {
            foreach($add as $i) {
                $count['added'] += $this->addToSet($column, $i, $clients);
            }
        }

        if($remove) {
            foreach($remove as $i) {
                $count['removed'] += $this->removeFromSet($column, $i, $clients);
            }
        }

        return $count;
    }

    /**
     * add to set
     * @param $column, $value, $ids
     * @return bool
     */
    private function addToSet($column, $value, $ids = [])
    {
        $query = Client::query();

        if(!empty($ids)) {
            $query = $query->whereIn('id', $ids);
        }

        // Remove first to avoid duplicates
        $this->removeFromSet($column, $value, $ids);

        $result = $query->update([
                $column => \DB::raw('CONCAT(' .$column.", ',', ".$value. ')')
            ]);

        return $result;
    }

    /**
     * remove from set
     * @param $column, $value, $ids
     * @return bool
     */
    private function removeFromSet($column, $value, $ids = [])
    {
        $query = Client::query();

        if(!empty($ids)) {
            $query = $query->whereIn('id', $ids);
        }

        $result = $query->update([
                $column => \DB::raw("
                    TRIM(BOTH ',' FROM
                      REPLACE(
                        REPLACE(CONCAT(',',REPLACE(".$column.", ',', ',,'), ','),',".$value.",', ''), ',,', ',')
                    )")
            ]);

        return $result;
    }

    public function teams($id)
    {
        return $this->client->teams($id);
    }
}
