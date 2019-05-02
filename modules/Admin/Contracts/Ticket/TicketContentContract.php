<?php

namespace Modules\Admin\Contracts\Ticket;

interface TicketContentContract
{
    /**
     * @param int $ticketId
     * @param string $type
     * @return mixed
     */
    public function getContent($ticketId, $type);

    /**
     * @param $ticketId
     * @param $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getContentByType($ticketId, $type);

    /**
     * @param int $ticketId
     * @param string $type
     * @param string $content
     */
    public function addTicketContent($ticketId, $type, $content);
}