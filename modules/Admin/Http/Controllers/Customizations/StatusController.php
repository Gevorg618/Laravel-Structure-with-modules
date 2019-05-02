<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Customizations\StatusRepository;
use Modules\Admin\Http\Requests\Customizations\ApprStatusRequest;

class StatusController extends AdminBaseController
{
    /**
     * Object of StatusRepository class
     *
     * @var statusRepo
     */
    private $statusRepo;
    
    /**
     * Create a new instance of StatusController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statusRepo = new StatusRepository();
    }

    /**
     * Index page for Appraisal Status
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::customizations.statuses.index');
    }

    /**
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $statuse
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            return $this->statusRepo->statusDataTables();
        }
    }

    /**
     * Create page for Appraisal Status
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {   
        return view('admin::customizations.statuses.create');
    }

    /**
     * create new order status
     *
     * @param Request $request
     *
     * @return array $types
     */
    public function store(ApprStatusRequest $request)
    {
        
        $createdStatus = $this->statusRepo->create($request->all());

        if ($createdStatus) {

            \Session::flash('success', 'Apprasial order Status was  successfully created!');

            return redirect()->route('admin.appraisal.appr-statuses.index');
        
        } else {

            \Session::flash('error', 'Something was wrong!');

            return redirect()->route('admin.appraisal.appr-statuses.index');
        }
    }

    /**
     * Edit page for Appraisal order Status
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $status = $this->statusRepo->getOne($id);
        return view('admin::customizations.statuses.edit', compact('status'));
    }

    /**
     * Update Appraisal Order data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, ApprStatusRequest $request)
    {
        $updatedStatus = $this->statusRepo->update($id, $request->all());

        if ($updatedStatus) {

            \Session::flash('success', 'Apprasial order Status was  successfully updated!');

            return redirect()->route('admin.appraisal.appr-statuses.index');
        
        } else {

            \Session::flash('error', 'Something was wrong!');

            return redirect()->route('admin.appraisal.appr-statuses.index');
        }
    }
}
