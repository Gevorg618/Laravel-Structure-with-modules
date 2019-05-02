<?php

namespace Modules\Admin\Repositories\Ticket;

use Modules\Admin\Contracts\Ticket\StatusContract;
use App\Models\Ticket\Status;

class StatusRepository implements StatusContract
{
    private $status;

    /**
     * StatusRepository constructor.
     *
     * @param Status $status
     */
    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStatuses()
    {
        return Status::orderBy('name', 'asc')->get();
    }
}