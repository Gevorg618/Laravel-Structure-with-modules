<?php

namespace Modules\Admin\Http\Controllers\Management;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Management\EmailTemplate;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\EmailTemplatesRequest;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;

class EmailTemplatesController extends AdminBaseController
{

    public function index()
    {
        return view('admin::management.email-templates.index');
    }

    /**
     * Processing datatables ajax
     * @param Request $request
     * @return mixed
     */
    public function emailTemplatesData(Request $request)
    {
        if ($request->ajax()) {
            $category =  array(
                'client' => 'Clients',
                'appr' => 'Appraisers',
                'sales' => 'Sales',
            );

            $emailTemplates = EmailTemplate::all();
            return Datatables::of($emailTemplates)
                ->editColumn('category',function($r) use ($category){
                    return $category[$r->category];
                })
                ->editColumn('created_at',function($r) use ($category){
                    return Carbon::createFromTimestamp($r->created_at);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.email-templates.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param EmailTemplatesRequest $request
     * @param EmailTemplate $emailTemplate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createEmailTemplate(EmailTemplatesRequest $request, EmailTemplate $emailTemplate)
    {
        $categories =  array(
            '' => 'Choose Category',
            'client' => 'Clients',
            'appr' => 'Appraisers',
            'sales' => 'Sales',
        );
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $emailTemplate->create($data);
            if ($create) {
                Session::flash('success', 'Email Template Created.');
                return redirect()->route('admin.management.email-templates');
            }
        }

        return view('admin::management.email-templates.create', compact('emailTemplate','categories'));
    }

    /**
     * @param EmailTemplatesRequest $request
     * @param EmailTemplate $emailTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmailTemplate(EmailTemplatesRequest $request, EmailTemplate $emailTemplate)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();

            $update = $emailTemplate->update($data);
            if ($update) {
                Session::flash('success', 'Email Template Updated.');
            }
        }
        return redirect()->route('admin.management.email-templates');
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteEmailTemplate(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        Session::flash('success', 'Email Template Deleted.');
        return redirect()->route('admin.management.email-templates');
    }
}
