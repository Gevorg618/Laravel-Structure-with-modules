<?php

namespace Modules\Admin\Repositories\Customizations;

use App\Models\Customizations\Status;
use Yajra\DataTables\Datatables;

class StatusRepository
{
    private $status;

    /**
     * StatusRepository constructor.
     */
    public function __construct()
    {
        $this->status = new Status();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function create($data)
    {
        return $this->status->create($data);
    }

    /**
     *  Get status by id
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOne($id)
    {
        return $this->status->findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function update($id, $data)
    {
        return $this->status->where('id', $id)->update($data);
    }

    /**
     * return all statuses
     *
     * @return array
     */
    public function statusDataTables()
    {

        $statuses = $this->status::query();

        return  Datatables::of($statuses)
                ->editColumn('id', function ($status) {
                    return $status->id;
                })
                ->editColumn('status_select_order', function ($status) {
                    return $status->status_select_order;
                })
                ->editColumn('client_title', function ($status) {
                        return  $status->client_title;
                })
                ->editColumn('appraiser_title', function ($status) {
                        return  $status->appraiser_title;
                })
                ->editColumn('block_appraiser_actions', function ($status) {
                        return $status->block_appraiser_actions? 'Yes' : 'No';
                })
                ->editColumn('options', function ($status) {
                    return view('admin::appraisal.statuses.partials._options', compact('status'))->render();
                })
                ->rawColumns(['options'])
                ->make(true);
    }


}
