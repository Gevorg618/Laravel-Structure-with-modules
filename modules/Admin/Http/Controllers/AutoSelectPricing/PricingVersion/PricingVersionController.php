<?php

namespace Modules\Admin\Http\Controllers\AutoSelectPricing\PricingVersion;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\AutoSelectPricing\PricingVersionRepository;
use App\Models\Customizations\{LoanReason, Addenda, LoanType};
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use App\Models\Clients\Client;

class PricingVersionController extends AdminBaseController
{

    /**
     * Object of PricingVersionRepository class
     *
     * @var pricingVersionRepo
     */
    private $pricingVersionRepo;

    /**
     * Object of StatesRepository class
     *
     * @var stateRepo
     */
    private $stateRepo;

    /**
     * Object of TypesRepository class
     *
     * @var typeRepo
     */
    private $typeRepo;

    /**
     * PricingVersionController constructor.
     */
    public function __construct()
    {
        $this->pricingVersionRepo = new PricingVersionRepository();
        $this->stateRepo = new StatesRepository();
        $this->typeRepo = new TypesRepository();
    }

    /**
    * Index
    * @return index page
    */
    public function index()
    {
        $pricingVersions = $this->pricingVersionRepo->getPricingVersions()->pluck('title', 'id');
        $clients = Client::select('id', 'descrip')->orderBy('descrip', 'ASC')->get()->pluck('descrip', 'id');
        
        return view('admin::auto_select_pricing.pricing-version.index', compact('pricingVersions', 'clients'));
    }

    /**
     * show 
     *
     * @param Request $request
     * 
     * @return response
     */
    public function data(Request $request)
    {   
        
        if ($request->ajax()) {

            //get dashboard statistiscs more info 
            $data = $this->pricingVersionRepo->data(
                $request->get('request_type'),
                $request->get('start'),
                $request->get('length')
            );
            return $data;
        } else {

            return redirect()->back();
        }
    }


    /**
     * pricing modal render
     * 
     * @return response
     */
    public function pricingView()
    {   
        $loanReasons = multiselect(LoanReason::all(), 'descrip')->prepend('-- Select Loan Reason --', null);
        return view('admin::auto_select_pricing.pricing-version.partials._pricing_new', compact('loanReasons'))->render();
    }

    /**
     * show 
     *
     * @param Request $request
     * 
     * @return response
     */
    public function pricingStore(Request $request)
    {   
        if ($request->ajax()) {

            $data = $this->pricingVersionRepo->createPricing($request->all());
            return response()->json($data);
        } else {

            return redirect()->back();
        }
    }

    /**
     * pricing modal render
     * 
     * @return response
     */
    public function pricingEdit($id)
    {   
        $pricing = $this->pricingVersionRepo->getPricing($id);
        $loanReasons = multiselect(LoanReason::all(), 'descrip')->prepend('-- Select Loan Reason --', null);
        return view('admin::auto_select_pricing.pricing-version.partials._pricing_edit', compact('loanReasons', 'pricing'))->render();
    } 

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingUpdate($id, Request $request)
    {   
        if ($request->ajax()) {

            $data = $this->pricingVersionRepo->updatePricing($id, $request->all());
            return response()->json($data);
        } else {

            return redirect()->back();
        }
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingClientsDownload($id)
    {   

        $pricing = $this->pricingVersionRepo->getPricing($id);

        if ($pricing) {
            $clients = $pricing->clients;

            $html = [];
            $html[] = "ID, Client";

            if ($clients) {
                foreach ($clients as $client) {
                    $html[] = sprintf("%s, %s", $client->id, str_replace(',', '', $client->descrip));
                }
            }

            header("Content-type: text/csv");
            header("Content-Disposition: attachment;f ilename='" . $pricing->title . ".csv'");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo implode("\n", $html);
            exit;
        } else {

        }
    } 

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingDownload($id)
    {   

        $data = $this->pricingVersionRepo->pricingDownload($id);

        $template = implode("\n", $data['data']);
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename='" . $data['title'] . '_' . date('m_d_y') . ".csv'");
        header("Pragma: no-cache");
        header("Expires: 0");
        print $template;

        exit;
    } 

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingEditAddendas($id)
    {   
        $pricing = $this->pricingVersionRepo->getPricing($id);
        $addendas = Addenda::all();
        return view('admin::auto_select_pricing.pricing-version.partials._pricing_addendas', compact('addendas', 'pricing'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingUpdateAddendas($id, Request $request)
    {   
        if ($request->ajax()) {
            $data = $this->pricingVersionRepo->updatePricingAddenda($id, $request->get('addendas'));
            return response()->json($data);
        } else {

            return redirect()->back();
        }
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingViewByState($id)
    {   
        $states = $this->stateRepo->getStates();
        $pricing = $this->pricingVersionRepo->getPricing($id);

        return view('admin::auto_select_pricing.pricing-version.partials._pricing_view_state', compact('pricing', 'states'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingViewByOneState($id, $state)
    {   
        $selectedAmounts  = $this->pricingVersionRepo->loadPricesByState($id, $state);

        $loanTypes = LoanType::all();
        $apprTypes = $this->typeRepo->createTypesArray();

        $pricing = $this->pricingVersionRepo->getPricing($id);
        
        return view('admin::auto_select_pricing.pricing-version.partials._appr_state_prices', compact('pricing', 'loanTypes', 'apprTypes', 'selectedAmounts', 'state'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingUpdateState($id, $state, Request $request)
    {   

        $prices = $request->get('prices');
        $this->pricingVersionRepo->getPricingStatePriceUpdate($id, $state, $prices);

        \Session::flash('success', "Prices was successfully updated !");

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingAddClient($id)
    { 
        $clients = Client::doesntHave('apprStatePrice')->select('id', 'descrip')->orderBy('descrip', 'ASC')->get()->pluck('descrip', 'id');
        $apprTypes =  $this->typeRepo->createTypesArray();
        $states = $this->stateRepo->getStates();

        // Get Values
        $values = [];
        $pricing =  $this->pricingVersionRepo->getPricing($id);
        $rows = $pricing->apprPricingVersions;

        foreach ($rows as $r) {
            $values[$r->state][$r->appr_type] = array('amount' => $r->amount, 'fha_amount' => $r->fha_amount);
        }
        
        return view('admin::auto_select_pricing.pricing-version.partials._add_client', compact('clients', 'apprTypes', 'states', 'values', 'pricing'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingStoreClient($id, Request $request)
    { 
        
        $this->pricingVersionRepo->storeApprStatePrices($request->get('client'), $request->get('prices'));

        \Session::flash('success', "Prices was successfully updated !");

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }
   

    /**
     * pricing modal render
     * 
     * @return response
     */
    public function pricingCustomEdit($clientId)
    {   
        
        $loanReasons = $this->pricingVersionRepo->getCustomSelectedPricingVersion($clientId);
        $loanResaonPublic  = $this->pricingVersionRepo->loanReasonAllPublic();

        return view('admin::auto_select_pricing.pricing-version.partials._client_form', compact('loanReasons', 'loanResaonPublic', 'clientId'))->render();
    }

    /**
     * pricing modal render
     * 
     * @return response
     */
    public function pricingCustomUpdate($clientId, Request $request)
    {   

        $saveData = $this->pricingVersionRepo->apprStateSaveLoanReason($clientId, $request->get('loan_reason'));

        if ($saveData) {
            \Session::flash('success', "Prices was successfully updated !");
        } else {
            \Session::flash('error', $saveData);
        }

        return redirect()->route('admin.autoselect.pricing.versions.index');
    } 

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingClientDownload($clientId)
    {   
        
        $data = $this->pricingVersionRepo->pricingClientDownload($clientId);

        $template = implode("\n", $data['data']);
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename='" . $data['client']->descrip . '_' . date('m_d_y') . ".csv'");
        header("Pragma: no-cache");
        header("Expires: 0");
        print $template;
        exit;
    } 

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingViewByClient($clientId)
    {   
        $states = $this->stateRepo->getStates();

        return view('admin::auto_select_pricing.pricing-version.partials._pricing_view_state', compact('clientId', 'states'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingViewByOneClient($clientId, $state)
    {   
        $selectedAmounts  = $this->pricingVersionRepo->loadPricesByClient($clientId, $state);

        $loanTypes = LoanType::all();
        $apprTypes = $this->typeRepo->createTypesArray();
        
        return view('admin::auto_select_pricing.pricing-version.partials._appr_state_prices', compact('clientId', 'loanTypes', 'apprTypes', 'selectedAmounts', 'state'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingUpdateClient($id, $state, Request $request)
    {   
        
        $data = $this->pricingVersionRepo->getPricingClientPriceUpdate($id, $state, $request->get('prices'));
        if ($data) {
            \Session::flash('success', "Client Prices was successfully updated !");
        } else {
            \Session::flash('error', $data);
        }
        

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingClientEditAddendas($clientId)
    {   

        $savedAddendas = $this->pricingVersionRepo->getSavedClientAddendas($clientId);

        $addendas = Addenda::all();

        return view('admin::auto_select_pricing.pricing-version.partials._pricing_custom_addendas', compact('savedAddendas', 'addendas', 'clientId'))->render();
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingClientUpdateAddendas($id, Request $request)
    {   


        $data = $this->pricingVersionRepo->updatePricingClientAddenda($id, $request->get('addendas'));

        if ($data) {
            \Session::flash('success', "Client Addendas was successfully updated !");
        } else {
            \Session::flash('error', $data);
        }

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function pricingClientDelete($id)
    {   

        
        $data = $this->pricingVersionRepo->deletePricingClient($id);

        if ($data) {
            \Session::flash('success', "Client Pricing was successfully deleted !");
        } else {
            \Session::flash('error', $data);
        }

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function templateDownload()
    {   
        $data = $this->pricingVersionRepo->downloadTemplateExample();

        $template = implode("\n", $data);
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=template.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        print $template;
        exit;
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function importVersion(Request $request)
    {   

        $data = $this->pricingVersionRepo->importVersion($request->get('version'), $request->file('version_file'));

        if ($data['success']) {
            \Session::flash('success', "Version was successfully imported !");
        } else {
            \Session::flash('error', $data);
        }

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }

    /**
     * pricing update
     * 
     * @return response
     */
    public function importClientVersion(Request $request)
    {   

        $data = $this->pricingVersionRepo->importClientVersion($request->get('client'), $request->file('client_file'));

        if ($data['success']) {
            \Session::flash('success', "Client Pricing Version was successfully imported !");
        } else {
            \Session::flash('error', $data);
        }

        return redirect()->route('admin.autoselect.pricing.versions.index');
    }
    
}