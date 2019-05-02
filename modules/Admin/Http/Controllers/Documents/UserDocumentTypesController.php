<?php
namespace Modules\Admin\Http\Controllers\Documents;

use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Documents\UserDocumentType;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Documents\UserDocumentTypesRequest;

class UserDocumentTypesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::document.user.types.index');
    }

    /**
     * Process datatables ajax request
     * @param Request $request
     * @return mixed
     */
    public function documentUserTypesData(Request $request)
    {
        if ($request->ajax()) {
            $userDocumentTypes = UserDocumentType::all();
            return Datatables::of($userDocumentTypes)
                ->editColumn('is_protected', function ($r) {
                    return ($r->is_protected) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::document.user.types.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param UserDocumentTypesRequest $request
     * @param UserDocumentType $userDocumentType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createUserDocumentType(UserDocumentTypesRequest $request, UserDocumentType $userDocumentType)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $userDocumentType->create($data);
            if ($create) {
                Session::flash('success', 'User Document Type Created.');
                return redirect()->route('admin.document.user.types');
            }
        }
        return view('admin::document.user.types.create', compact('userDocumentType'));
    }

    /**
     * @param UserDocumentTypesRequest $request
     * @param UserDocumentType $userDocumentType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserDocumentType(UserDocumentTypesRequest $request, UserDocumentType $userDocumentType)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $userDocumentType->update($data);
            if ($update) {
                Session::flash('success', 'User Document Type Updated.');
            }
        }
        return redirect()->route('admin.document.user.types');
    }

    /**
     * @param UserDocumentType $userDocumentType
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteUserDocumentType(UserDocumentType $userDocumentType)
    {
        $userDocumentType->delete();
        Session::flash('success', 'User Document Type Deleted.');
        return redirect()->route('admin.document.user.types');
    }
}