<?php

namespace Modules\Admin\Contracts\Ticket;

interface ActivityContract
{
    /**
     * @param int $ticketId
     * @param string $message
     * @return void
     */
    public function addTicketActivity($ticketId, $message);

    /**
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivity($limit = 100);
}