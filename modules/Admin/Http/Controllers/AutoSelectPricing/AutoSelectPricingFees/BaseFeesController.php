<?php

namespace Modules\Admin\Http\Controllers\AutoSelectPricing\AutoSelectPricingFees;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\AutoSelectPricing\AutoSelectPricingGroupFeeRepository;
use Modules\Admin\Repositories\AutoSelectPricing\AutoSelectPricingVersionFeeRepository;
use Modules\Admin\Repositories\Geo\StatesRepository;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Modules\Admin\Helpers\Excel;

class BaseFeesController extends AdminBaseController
{
    
    /**
     * Object of AutoSelectPricingGroupFeeRepository class
     *
     * @var pricingGroupFeeRepo
     */
    public $pricingGroupFeeRepo;

    /**
     * Object of AutoSelectPricingVersionFeeRepository class
     *
     * @var pricingVersionFeeRepo
     */
    public $pricingVersionFeeRepo;

    /**
     * Object of StatesRepository class
     *
     * @var statesRepository
     */
    public $statesRepository;

    /**
     * Object of TypesRepository class
     *
     * @var typesRepository
     */
    public $typesRepository;

    /**
     * Object of \Modules\Admin\Helpers\Excel class
     *
     * @var typesRepository
     */
    public $excel;

    /**
     * Create a new instance of AutoSelectPricingVersionFeesController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pricingGroupFeeRepo = new AutoSelectPricingGroupFeeRepository();
        $this->pricingVersionFeeRepo = new AutoSelectPricingVersionFeeRepository();
        $this->statesRepository  = new StatesRepository();
        $this->typesRepository = new TypesRepository();
        $this->excel = new Excel();
    }   

    /**
     * GET /admin/autoselect-pricing/version-fees/index
     *
     * Auto Select Pricing Version Fees index page
     *
     * @return view
     */
    public function index()
    {
     
        $groups = $this->pricingGroupFeeRepo->notExistingGroups()->pluck('descrip', 'id')->prepend('-- Select Group --', '');
        $createdGroups = $this->pricingGroupFeeRepo->createdGroups();
        $versionFees = $this->pricingVersionFeeRepo->statePricingVersionFees();
        $suppostedToBe = $this->pricingGroupFeeRepo->suppostedToBe();
        
        return view('admin::auto_select_pricing.version-fees.index', compact('groups', 'createdGroups', 'versionFees', 'suppostedToBe'));
    }
}