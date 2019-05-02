<?php 

namespace Modules\Admin\Http\Controllers\Tools;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Requests\Tools\UpdateCustomPagesManagerRequest;
use Modules\Admin\Http\Requests\Tools\CreateCustomPagesManagerRequest;
use Modules\Admin\Repositories\Tools\CustomPagesManagerRepository;
use App\Models\Tools\CustomPage;
use App\Models\Tools\Template;
use Yajra\DataTables\Datatables;

class CustomPagesManagerController extends AdminBaseController 
{
    /**
     * Object of CustomPagesManagerRepository class
     *
     * @var customPagesManagerRepo
     */
    private $customPagesManagerRepo;
    
    /**
     * Create a new instance of CustomPagesManagerController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customPagesManagerRepo = new CustomPagesManagerRepository();
    }

    /**
    * GET /admin/tools/custom-pages-manager/index
    *
    * Custom Pages index page
    *
    * @return view
    */
    public function index()
    {
        return view('admin::tools.custom-pages-manager.index');
    }

    /**
    * GET /admin/tools/custom-pages-manager/create
    *
    * Create Custom Page page
    *
    * @return view
    */
    public function create()
    {
        
        // get templates for select options 
        $templates = Template::get()->pluck('name', 'id')->prepend('-- Select Template --', '');
        
        return view('admin::tools.custom-pages-manager.create', ['templates' => $templates]);
    }

    /** 
    * POST /admin/tools/custom-pages-manager/create
    *
    * create CustomPage
    *
    * @param CreateCustomPagesManagerRequest $request
    * 
    * @return view
    */
    public function store(CreateCustomPagesManagerRequest $request)
    {
        
        $customPage = $this->customPagesManagerRepo->create($request);
        
        if ($customPage['success']) {

            $customPageData = $customPage['data'];

            Session::flash('success', $customPage['message']);

            return redirect()->route('admin.tools.custom-pages-manager.index');
        
        } else {

            Session::flash('error', $customPage['message']);

            return redirect()->route('admin.tools.custom-pages-manager.index');
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function showCustomPage($id)
    {
        return $this->customPagesManagerRepo->showCustomPage($id);
    }

    /**
     * GET /admin/tools/custom-pages-manager/data
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $customPages
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {

            $customPages = $this->customPagesManagerRepo->customPagesDataTables();

            return $customPages;
        }
    }

    /**
    * GET /admin/tools/custom-pages-manager/delete
    *
    * Delete Custom Page 
    *
    * @param integer $id
    *
    * @return view
    */
    public function delete($id)
    {
        $isDeleted = $this->customPagesManagerRepo->delete($id);

        if ($isDeleted) {

            Session::flash('success', 'Custom Page is deleted.');
            
            return redirect()->route('admin.tools.custom-pages-manager.index');

        } else {

            Session::flash('error', 'Custom Page is not found.');
            
            return redirect()->route('admin.tools.custom-pages-manager.index');
        }
    }

    /**
    * GET /admin/tools/custom-pages-manager/edit
    *
    * Edit Custom Page page
    *
    * @param integer $id
    *
    * @return view
    */
    public function edit($id)
    {
        $customPage = $this->customPagesManagerRepo->findById($id);

        if ($customPage) {

            // get templates for select options 
            $templates = Template::get()->pluck('name', 'id')->prepend('-- Select Template --', '');

            return view('admin::tools.custom-pages-manager.edit', compact('customPage', 'templates'));

        } else {
            
            return redirect()->route('admin.tools.custom-pages-manager.index');
        }
    }

    /**
    * PUT /admin/tools/custom-pages-manager/update
    *
    * Update Custom Page 
    *
    * @param integer $id
    * @param Request $request
    *
    * @return view
    */
    public function update($id, UpdateCustomPagesManagerRequest $request)
    {
        $isUpdated = $this->customPagesManagerRepo->update($id, $request);

        if ($isUpdated['success']) {

            Session::flash('success', $isUpdated['message']);

            return redirect()->route('admin.tools.custom-pages-manager.index');
        
        } else {

            Session::flash('error', $isUpdated['message']);

            return redirect()->route('admin.tools.custom-pages-manager.index');
        }


    }
}