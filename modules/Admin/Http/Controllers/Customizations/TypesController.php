<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Modules\Admin\Http\Requests\Customizations\TypeRequest;

class TypesController extends AdminBaseController
{
    /**
     * Object of TypesRepository class
     *
     * @var typeRepo
     */
    private $typeRepo;
    
    /**
     * Create a new instance of TypesController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->typeRepo = new TypesRepository();
    }

    /**
     * Index page for Appraisal Types
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::appraisal.types.index');
    }

    /**
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $types
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {

            $types = $this->typeRepo->typesDataTables();

            return $types;
        }
    }

    /**
     * Create page for Appraisal Types
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::appraisal.types.create');
    }

    /**
     * create new order type
     *
     * @param TypeRequest $request
     *
     * @return array $types
     */
    public function store(TypeRequest $request)
    {

        $createdType = $this->typeRepo->create($request->all());
        if ($createdType) {

            \Session::flash('success', 'Apprasial order Type was  successfully created!');

            return redirect()->route('admin.appraisal.appr-types.index');
        
        } else {

            \Session::flash('error', 'Something was wrong!');

            return redirect()->route('admin.appraisal.appr-types.index');
        }
    }

    /**
     * Edit page for Appraisal Types
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $type = $this->typeRepo->getOne($id);

        return view('admin::appraisal.types.edit', compact('type'));
    }

    /**
     * Update Appraisal Type data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, TypeRequest $request)
    {

        $updatedType = $this->typeRepo->update($id, $request->all());

        if ($updatedType) {

            \Session::flash('success', 'Apprasial order Type was  successfully updated!');

            return redirect()->route('admin.appraisal.appr-types.index');
        
        } else {

            \Session::flash('error', 'Something was wrong!');

            return redirect()->route('admin.appraisal.appr-types.index');
        }
    }
}
