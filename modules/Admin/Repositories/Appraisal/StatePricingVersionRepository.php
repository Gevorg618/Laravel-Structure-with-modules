<?php


namespace Admin\Repositories\Appraisal;


use App\Models\Appraisal\StatePricingVersion;

class StatePricingVersionRepository
{
    /**
     * Object of StatePricingVersion class
     */
    private $model;


    /**
     * StatePricingVersionRepository constructor.
     */
    public function __construct()
    {
        $this->model = new StatePricingVersion();

    }


    /**
     * @return mixed
     */
    public function statePricingVersionList()
    {
        return $this->model->orderBy('title', 'asc')->pluck('title', 'id');
    }
}
