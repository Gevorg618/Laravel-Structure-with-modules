<?php
namespace Modules\Admin\Http\Controllers\Documents;

use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Documents\DocumentType;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Documents\DocumentTypesRequest;

class DocumentTypesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::document.types.index');
    }

    /**
     * Process datatables ajax request
     * @param Request $request
     * @return mixed
     */
    public function documentTypesData(Request $request)
    {
        if ($request->ajax()) {
            $documentTypes = DocumentType::all();
            return Datatables::of($documentTypes)
                ->editColumn('is_protected', function ($r) {
                    return ($r->is_protected) ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::document.types.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param DocumentTypesRequest $request
     * @param DocumentType $documentType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createDocumentType(DocumentTypesRequest $request, DocumentType $documentType)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $documentType->create($data);
            if ($create) {
                Session::flash('success', 'Document Type Created.');
                return redirect()->route('admin.document.types');
            }
        }
        return view('admin::document.types.create', compact('documentType'));
    }

    /**
     * @param DocumentTypesRequest $request
     * @param DocumentType $documentType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDocumentType(DocumentTypesRequest $request, DocumentType $documentType)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $documentType->update($data);
            if ($update) {
                Session::flash('success', 'Document Type Updated.');
            }
        }
        return redirect()->route('admin.document.types');
    }

    /**
     * @param DocumentType $documentType
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteDocumentType(DocumentType $documentType)
    {
        $documentType->delete();
        Session::flash('success', 'Document Type Deleted.');
        return redirect()->route('admin.document.types');
    }
}