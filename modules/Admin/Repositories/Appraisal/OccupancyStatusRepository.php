<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Customizations\OccupancyStatus;

class OccupancyStatusRepository
{
    private $occupancyStatus;

    /**
     * OccupancyStatusRepository constructor.
     */
    public function __construct()
    {
        $this->occupancyStatus = new OccupancyStatus();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function occupancyStatuses()
    {
        return $this->occupancyStatus->get();
    }


    /**
     * @return mixed
     */
    public function occupancyStatusesList()
    {
        return $this->occupancyStatus->orderBy('descrip', 'asc')->pluck('descrip', 'id');
    }
}
