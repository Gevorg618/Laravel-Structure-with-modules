<?php

namespace Modules\Admin\Http\Controllers\Ticket;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Ticket\TicketStatusRequest;
use Yajra\DataTables\Facades\Datatables;

use App\Models\Ticket\Status;

class TicketStatusesController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::ticket.statuses.index');
    }

    /**
     * Gather data for index page and datatables
     *
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $statuses = Status::all();

            return Datatables::of($statuses)
                ->addColumn('action', function ($r) {
                    return view('admin::ticket.statuses.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::ticket.statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TicketStatusRequest $request
     * @param Status $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TicketStatusRequest $request, Status $status)
    {
        $status->store($request);
        Session::flash('success', 'Ticket Status Successfully Created.');

        return redirect()->route('admin.ticket.statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $status = Status::findOrFail($id);
        return view('admin::ticket.statuses.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TicketStatusRequest $request
     * @param Status $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TicketStatusRequest $request, Status $status)
    {
        $status->store($request);
        Session::flash('success', 'Ticket Status Successfully Updated.');

        return redirect()->route('admin.ticket.statuses.index');
    }

}
