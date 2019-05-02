<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Customizations\Type;
use App\Models\Customizations\{LoanReason,  PropertyType, LoanType};
use Modules\Admin\Repositories\Clients\ClientRepository;
use App\Models\Clients\Client;
use Carbon\Carbon;
use Session;

class ClientBulkChangeToolController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index(Type $type)
    {
        $clients = Client::getAllClients();
        $apprView = $this->apprView($clients, $type);
        $loanTypesView = $this->loanTypesView($clients);
        $propertyTypesView = $this->propertyTypesView($clients);
        $loanReasonsView = $this->loanReasonsView($clients);

        return view('admin::customizations.client-bulk-tool.index',
                compact(
                        'apprView',
                        'loanTypesView',
                        'propertyTypesView',
                        'loanReasonsView'
                    )
            );
    }

    /**
    * Appr Tab
    * @return view
    */
    private function apprView($clients, $type)
    {
        $apprTypes = $type->allTypes();
        return view('admin::customizations.client-bulk-tool.partials._appr',
                compact(
                        'clients',
                        'apprTypes'
                    )
            );
    }

    /**
    * Loan Types Tab
    * @return view
    */
    private function loanTypesView($clients)
    {
        $loanTypes = LoanType::getLoanTypes();
        return view('admin::customizations.client-bulk-tool.partials._loan_types',
                compact(
                        'clients',
                        'loanTypes'
                    )
            );
    }

    /**
    * Property Types Tab
    * @return view
    */
    private function propertyTypesView($clients)
    {
        $properties = PropertyType::getTypes();
        return view('admin::customizations.client-bulk-tool.partials._property_types',
                compact(
                        'clients',
                        'properties'
                    )
            );
    }

    /**
    * Loan Reasons Tab
    * @return view
    */
    private function loanReasonsView($clients)
    {
        $reasons = LoanReason::getReasons();
        return view('admin::customizations.client-bulk-tool.partials._loan_reasons',
                compact(
                        'clients',
                        'reasons'
                    )
            );
    }

    /**
    * update Client
    * @param $request, $clientRepository
    * @return response
    */
    public function update(
            Request $request,
            ClientRepository $clientRepository
        )
    {
        $inputs = $request->all();
        $column = $inputs['column'];
        $count = $clientRepository->updateClient($inputs, $column);

        if (is_null($count)) {
            Session::flash('error', 'Sorry, You must select either to add or remove records.');
            return redirect()->back();
        }

        Session::flash('success', sprintf('Operation Completed. %s Appraisals Added, %s Removed', $count['added'], $count['removed']));
        return redirect()->back();
    }
}
