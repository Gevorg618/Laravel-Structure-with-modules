<?php
    
namespace Modules\Admin\Http\Controllers\Ticket;

use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Management\UserType;
use App\Models\Customizations\Status;
use App\Models\Ticket\Category;
use Modules\Admin\Http\Requests\SupportTickets\CategoryRequest;
use Yajra\Datatables\Datatables;
use Session;

class CategoriesController extends AdminBaseController
{
    
    /**
     * Index page for Support Tickets (categories)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::ticket.categories.index');
    }

    /**
     * create form view for Support Tickets (categories) 
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user_types = userAllTypes();
        $statuses = appraisalStatuses();
        return view('admin::ticket.categories.create_edit', compact('user_types', 'statuses'));
    }

    /**
     * store resource for Support Tickets (categories)
     *
     * @return \Response
     */
    public function store(CategoryRequest $request)
    {
        $inputs = $request->all();
        $user_type_id = $request->userTypeInput();
        $order_status_id = $request->orderStatusInput();
        $category = Category::create($inputs);
        $category->setUserType($user_type_id);
        $category->setStatus($order_status_id);
        Session::flash('success', 'Category created.');
        return redirect()->route('admin.ticket.categories.index');
    }

    /**
     * Yajra\Datatables\Datatables api route for front-end
     *
     * @return \Response
     */

    public function data()
    {
        $categories = Category::all();
        return Datatables::of($categories)
            ->addColumn('action', function ($r) {
                return view('admin::ticket.categories.partials._options', ['row' => $r]);
            })
            ->make(true);
    }

    /**
     * edit form view for Support Tickets (categories) 
     * @param string $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $category->load(['orderStatuses', 'userTypes']);
        $user_types = userAllTypes();
        $statuses = appraisalStatuses();
        return view('admin::ticket.categories.create_edit', compact('user_types', 'statuses', 'category'));
    }

    /**
     * update resource for Support Tickets (categories)
     * @param string $id
     * @return \Response
     */
    public function update($id, CategoryRequest $request)
    {
        $category = Category::findOrFail($id);
        $inputs = $request->all();
        $category->update($inputs);
        $user_type_id = $request->userTypeInput();
        $order_status_id = $request->orderStatusInput();
        $category->updateUserType($user_type_id);
        $category->updateStatus($order_status_id);
        Session::flash('success', 'Category updated.');
        return redirect()->route('admin.ticket.categories.index');
    }

    /**
     * destroy resource for Support Tickets (categories)
     * @param string $id
     * @return \Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        Session::flash('success', 'Category deleted.');
        return redirect()->route('admin.ticket.categories.index');
    }    
}