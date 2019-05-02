<?php

namespace Modules\Admin\Repositories\Document;

use App\Models\Documents\OrderLocation;

class OrderLocationRepository
{
	/**
     * Object of OrderLocation class
     *
     * @var $orderLocation
     */
    private $orderLocation;

    /**
     * OrderLocationRepository constructor.
     */
    public function __construct()
    {
        $this->orderLocation = new OrderLocation();
    }

	/**
     * get all lcoations
     * 
     * @return collection
     */
    public function locations()
    {
        return $this->orderLocation->get();
    }
}