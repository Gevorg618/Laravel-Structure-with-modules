<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use App\Models\Customizations\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Customizations\PropertyTypesRequest;
use Yajra\DataTables\Facades\Datatables;

class PropertyTypesController extends AdminBaseController
{
    
    /**
     * Index page for AppraisalPropertyType
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::customizations.property-types.index');
    }
    
    /**
     * Gather data for index page and datatables
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $propertyTypes = PropertyType::all();
        
            return Datatables::of($propertyTypes)
                ->addColumn('action', function ($r) {
                    return view('admin::customizations.property-types.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }
    
    /**
     * Create new property type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::customizations.property-types.create');
    }
    
    /**
     * Store new property type
     *
     * @param PropertyTypesRequest $request
     * @param PropertyType $propertyType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PropertyTypesRequest $request, PropertyType $propertyType)
    {
        $propertyType->store($request);
        Session::flash('success', 'Property Type Successfully Created.');
    
        return redirect()->route('admin.appraisal.property-types.index');
    }
    
    /**
     * Edit property type details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $propertyType = PropertyType::findOrFail($id);
    
        return view('admin::customizations.property-types.edit', compact('propertyType'));
    }
    
    /**
     * Update Property type details
     *
     * @param PropertyTypesRequest $request
     * @param PropertyType $propertyType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PropertyTypesRequest $request, PropertyType $propertyType)
    {
        $propertyType->store($request);
        Session::flash('success', 'Property Type Successfully Updated.');
    
        return redirect()->route('admin.appraisal.property-types.index');
    }
    
    /**
     * Delete property type - soft delete
     *
     * @param PropertyType $propertyType
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(PropertyType $propertyType)
    {
        if ($propertyType->isProtected()) {
            Session::flash('error', 'You cannot delete protected property type.');
        
            return redirect()->route('admin.appraisal.property-types.index');
        }
    
        Session::flash('success', 'Property type is deleted.');
        $propertyType->delete();
    
        return redirect()->route('admin.appraisal.property-types.index');
    }
    
    
}
