<?php

namespace Modules\Admin\Contracts\Ticket;

interface TicketContract
{
    /**
     * @param int $teamId
     * @param string $teamTitle
     * @return array
     */
    public function getTeamStats($teamId, $teamTitle);

    /**
     * @param $ticketData
     * @return string|null
     */
    public function getTicketAssignTitle($ticketData);

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNextTicket($id);

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findTickets($request);

    /**
     * @param $ticket
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedTickets($ticket, $limit = 500);
}