<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Clients\Client;
use App\Models\Customizations\LoanReason;
use App\Models\Customizations\LoanType;
use App\Models\Customizations\Type;
use App\Models\Appraisal\UW\Checklist;
use App\Models\Appraisal\UW\ChecklistCategory;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\UW\CategoryRequest;
use Modules\Admin\Http\Requests\UW\QuestionRequest;

class UWChecklistController extends AdminBaseController
{
    public function index()
    {
        $categories = ChecklistCategory::all();
        return view('admin::appraisal.under-writing.checklist.index',compact('categories'));
    }

    /**
     * @param CategoryRequest $request
     * @param ChecklistCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createUwCategory(CategoryRequest $request,ChecklistCategory $category)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $category->create($data);
            if ($create) {
                Session::flash('success', 'Category Created.');
                return redirect()->route('admin.appraisal.under-writing.checklist');
            }
        }
        return view('admin::appraisal.under-writing.checklist.category_create', compact('category'));
    }

    /**
     * @param CategoryRequest $request
     * @param ChecklistCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUwCategory(CategoryRequest $request, ChecklistCategory $category)
    {
        if($request->isMethod('put')) {
            $data = $request->all();
            $update = $category->update($data);
            if ($update) {
                Session::flash('success', 'Category Updated.');
            }
        }
        return redirect()->route('admin.appraisal.under-writing.checklist');
    }

    /**
     * @param ChecklistCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoryMakeActiveInactive(ChecklistCategory $category)
    {
        $data['is_active'] = ($category->is_active)?0:1;
        $update = $category->update($data);
        if ($update) {
            Session::flash('success', 'Category Updated.');
        }
        return redirect()->route('admin.appraisal.under-writing.checklist');
    }

    /**
     * @param ChecklistCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCategory(ChecklistCategory $category)
    {
        $category->delete();
        Session::flash('success', 'Category Deleted.');
        return redirect()->route('admin.appraisal.under-writing.checklist');
    }

    /**
     * @param QuestionRequest $request
     * @param Checklist $question
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createQuestion(QuestionRequest $request,Checklist $question)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $create = $question->create($data);
            if ($create) {
                $question->saveRelations($create,$data);
                Session::flash('success', 'Question Created.');
                return redirect()->route('admin.appraisal.under-writing.checklist');
            }
        }
        $categories = multiselect(ChecklistCategory::all(),'title');
        $selected_category = $request->get('category');
        $categories->prepend('-- Select Category --','');
        $question->appr_type_all = multiselect(Type::all(), ['form', 'short_descrip']);
        $question->loan_type_all = multiselect(LoanType::all(), 'descrip');
        $question->clients_all = multiselect(Client::all(), 'descrip');
        $question->lenders_all = multiselect(UserGroupLender::all(), 'lender');
        $question->loan_reason_all = multiselect(LoanReason::all(), 'descrip');
        return view('admin::appraisal.under-writing.checklist.question_create', compact('question','categories','selected_category'));
    }

    /**
     * @param QuestionRequest $request
     * @param Checklist $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQuestion(QuestionRequest $request,Checklist $question)
    {
        if($request->isMethod('put')) {
            $data = $request->all();
            $create = $question->update($data);
            if ($create) {
                $question->updateRelations($question,$data);
                Session::flash('success', 'Question Updated.');
            }
        }
        return redirect()->route('admin.appraisal.under-writing.checklist');
    }

    /**
     * @param Checklist $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteQuestion(Checklist $question)
    {
        $question->delete();
        Session::flash('success', 'Question Deleted.');
        return redirect()->route('admin.appraisal.under-writing.checklist');
    }

}
