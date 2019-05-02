<?php

namespace Modules\Admin\Contracts\Ticket;

interface TicketFileContract
{
    /**
     * @param $request
     * @return string
     */
    public function getImage($request);
}