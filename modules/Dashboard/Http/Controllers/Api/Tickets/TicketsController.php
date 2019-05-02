<?php

namespace Dashboard\Http\Controllers\Api\Tickets;

use Dashboard\Models\Tickets\Ticket;
use Dashboard\Services\Tickets\TicketsService;
use Dashboard\Http\Controllers\DashboardBaseController;

class TicketsController extends DashboardBaseController
{
    protected $service;

    /**
     * TicketsController constructor.
     * @param TicketsService $service
     */
    public function __construct(TicketsService $service)
    {
        $this->service = $service;
    }

    public function list()
    {
        $rows = $this->service->tickets(request()->all());
        return response()->json($rows);
    }

    public function view()
    {
        $ticket = Ticket::query()
                          ->select(['id', 'subject'])
                          ->with(['publicComments' => function($query) {
                            $query->select(['html_content', 'id', 'ticket_id', 'created_date', 'created_by'])
                                  ->orderby('created_date', 'DESC');
                          }, 'publicComments.user' => function($query) {
                            $query->select(['id', 'user_type']);
                          }, 'contentText', 'contentHtml'])
                          ->ofHashedId(request()->get('hash'))
                          ->first();
        
        if(!$ticket) {
          return response()->json('Not Found', 404);
        }

        return response()->json($ticket);
    }
}
