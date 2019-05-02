<?php

namespace Modules\Admin\Contracts\Ticket;

interface OrderContract
{
    /**
     * @param string $term
     * @return array
     */
    public function searchOrders($term);

    /**
     * Get Delayed Data
     * @return mixed
     */
    public function getDelayedData();

    /**
     * Get Order  Data
     * @return mixed
     */
    public function getFilteredData($data);
}
