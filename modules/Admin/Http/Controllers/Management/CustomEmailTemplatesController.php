<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Helpers\Widget;
use App\Models\Customizations\LoanType;
use App\Models\Geo\State;
use App\Models\Customizations\Type;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use App\Models\Management\CustomEmailTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Management\CustomEmailTemplatesRequest;
use Yajra\DataTables\Datatables;

class CustomEmailTemplatesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::management.custom-email-templates.index');
    }

    /**
     * Processing datatables ajax
     * @param Request $request
     * @return mixed
     */
    public function customEmailTemplatesData(Request $request)
    {
        if ($request->ajax()) {
            $customEmailTemplates = CustomEmailTemplate::get();
            return Datatables::of($customEmailTemplates)
                ->editColumn('created_date', function ($r) {
                    return Carbon::createFromTimestamp($r->created_date);
                })
                ->editColumn('last_updated_date', function ($r) {
                    return Carbon::createFromTimestamp($r->last_updated_date);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::management.custom-email-templates.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param CustomEmailTemplatesRequest $request
     * @param CustomEmailTemplate $customEmailTemplate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createCustomEmailTemplate(
        CustomEmailTemplatesRequest $request,
        CustomEmailTemplate $customEmailTemplate
    ) {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $create = $customEmailTemplate->create($data);
            if ($create) {
                $customEmailTemplate->saveRelations($create,$data);
                Session::flash('success', 'Custom Email Template Created.');
                return redirect()->route('admin.management.custom-email-templates');
            }
        }
        $customEmailTemplate->appr_type_all = multiselect(Type::all(), ['form', 'short_descrip']);
        $customEmailTemplate->loan_type_all = multiselect(LoanType::all(), 'descrip');
        $customEmailTemplate->clients_all = multiselect(Client::all(), 'descrip');
        $customEmailTemplate->lenders_all = multiselect(UserGroupLender::all(), 'lender');
        $customEmailTemplate->states_all = multiselect(State::all(), 'state');
        return view('admin::management.custom-email-templates.create', compact('customEmailTemplate'));
    }

    /**
     * @param CustomEmailTemplatesRequest $request
     * @param CustomEmailTemplate $customEmailTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCustomEmailTemplate(
        CustomEmailTemplatesRequest $request,
        CustomEmailTemplate $customEmailTemplate
    ) {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $update = $customEmailTemplate->update($data);
            $customEmailTemplate->updateRelations($customEmailTemplate,$data);
            Session::flash('success', 'Custom Email Template Updated.');
            return redirect()->route('admin.management.custom-email-templates');

        }
        return redirect()->route('admin.management.custom-email-templates');
    }

    /**
     * @param CustomEmailTemplate $customEmailTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCustomEmailTemplate(CustomEmailTemplate $customEmailTemplate)
    {
        $customEmailTemplate->delete();
        Session::flash('success', 'Custom Email Template Deleted.');
        return redirect()->route('admin.management.custom-user-templates');
    }
}
