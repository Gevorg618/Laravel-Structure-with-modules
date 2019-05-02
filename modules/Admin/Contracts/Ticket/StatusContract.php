<?php

namespace Modules\Admin\Contracts\Ticket;

interface StatusContract
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStatuses();
}