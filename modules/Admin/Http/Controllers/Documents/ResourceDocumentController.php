<?php
namespace Modules\Admin\Http\Controllers\Documents;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Documents\ResourceDocument;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Documents\ResourceDocumentRequest;
use App\Models\Users\User;

class ResourceDocumentController extends AdminBaseController
{
    public function index()
    {
        return view('admin::document.resource.index');
    }

    /**
     * Process datatables ajax request
     * @param Request $request
     * @return mixed
     */
    public function resourceData(Request $request)
    {
        if ($request->ajax()) {
            $resource = ResourceDocument::with('user')->get();
            return Datatables::of($resource)
                ->editColumn('link', function ($r) {
                    return '<a target="_blank" href="'.$r->link.'">View</a>';
                })
                ->editColumn('created_by', function ($r) {
                    return $r->user->firstname.' '.$r->user->lastname;
                })
                ->addColumn('action', function ($r) {
                    return view('admin::document.resource.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param ResourceDocumentRequest $request
     * @param ResourceDocument $resource
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createResource(ResourceDocumentRequest $request, ResourceDocument $resource)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $resource->create($data);
            if ($create) {
                Session::flash('success', 'Resource Document Created.');
                return redirect()->route('admin.document.resource');
            }
        }
        return view('admin::document.resource.create', compact('resource'));
    }

    /**
     * @param ResourceDocumentRequest $request
     * @param ResourceDocument $resource
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateResource(ResourceDocumentRequest $request, ResourceDocument $resource)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $resource->update($data);
            if ($update) {
                Session::flash('success', 'Resource Document Updated.');
            }
        }
        return redirect()->route('admin.document.resource');
    }

    /**
     * @param ResourceDocument $resource
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteResource(ResourceDocument $resource)
    {
        $resource->delete();
        Session::flash('success', 'Resource Document Deleted.');
        return redirect()->route('admin.document.resource');
    }

}