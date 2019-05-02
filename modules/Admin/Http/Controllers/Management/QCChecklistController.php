<?php

namespace Modules\Admin\Http\Controllers\Management;

use App\Models\Customizations\LoanReason;
use App\Models\Customizations\LoanType;
use App\Models\Appraisal\QC\Checklist;
use App\Models\Appraisal\QC\ChecklistCategory;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\QC\CategoryRequest;
use Modules\Admin\Http\Requests\QC\ChecklistRequest;
use Modules\Admin\Repositories\Customizations\TypesRepository;
use Modules\Admin\Repositories\Management\ChecklistRepository;

/**
 * Class ChecklistController
 * @package Modules\Admin\Http\Controllers\QC
 */
class QCChecklistController extends Controller
{
    /**
     * @var ChecklistRepository
     */
    protected $checklistRepo;

    /**
     * @var TypesRepository
     */
    protected $typesRepo;

    /**
     * ChecklistController constructor.
     * @param $checklistRepo
     */
    public function __construct(
        ChecklistRepository $checklistRepo,
        TypesRepository $typesRepo
    )
    {
        $this->checklistRepo = $checklistRepo;
        $this->typesRepo = $typesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $cats = ChecklistCategory::orderBy('ord')->get();
        $categoriesQuestionsCount = $this->checklistRepo->getCategoriesQuestionsCount();
        $categoriesQuestions = $this->checklistRepo->getCategoryQuestions();
        return view('admin::management.checklist.index', [
            'cats' => $cats,
            'categoriesQuestionsCount' => $categoriesQuestionsCount,
            'categoriesQuestions' => $categoriesQuestions,
        ]);
    }

    public function lendersData()
    {
        $lenders = $this->checklistRepo->getLenders();
        $lenderIds = $lenders->pluck('id')->unique();
        $lenders = $lenders->pluck('lender', 'id')->unique();
        $lenderQuestions = $this->checklistRepo->getLenderSpecificQuestions($lenderIds);
        return response()->json([
            'html' => view('admin::management.checklist.partials.lenders_data', [
                'lenders' => $lenders,
                'lendersQuestions' => $lenderQuestions,
            ])->render(),
        ]);
    }

    public function clientsData()
    {
        $clients = $this->checklistRepo->getClients();
        $clientIds = $clients->pluck('id')->unique();
        $clients = $clients->pluck('descrip', 'id')
            ->unique();
        $clientQuestions = $this->checklistRepo->getClientSpecificQuestions($clientIds);
        return response()->json([
            'html' => view('admin::management.checklist.partials.clients_data', [
                'clients' => $clients,
                'clientsQuestions' => $clientQuestions,
            ])->render(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::management.checklist.create', [
            'cats' => ChecklistCategory::pluck('title', 'id'),
            'parentQuestionsList' => [],
            'clients' => Client::pluck('descrip', 'id'),
            'lenders' => UserGroupLender::pluck('lender', 'id'),
            'loanTypes' => LoanType::pluck('descrip', 'id'),
            'appraisalTypes' => $this->typesRepo->getTypesForMultiSelect(),
            'loanReasons' => LoanReason::pluck('descrip', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  ChecklistRequest $request
     * @return Response
     */
    public function store(ChecklistRequest $request)
    {
        $data = $request->all();
        $question = new Checklist();
        $question = $question->setData($data);
        \DB::beginTransaction();
        try {
            if ($question->save()) {
                $question->removeRelations();
                $question->saveRelations($data);
                \DB::commit();
                return redirect(route('admin.qc.checklist.index'))->
                with('success', 'Question has been successfully created');
            }
            return redirect(route('admin.qc.checklist.index'))->
            with('error', 'Something went wrong');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
            return redirect(route('admin.qc.checklist.index'))->
            with('error', 'Something went wrong');
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Checklist $question)
    {
        $parentQuestionsList = $this->checklistRepo->makeParentQuestionsDropdown($question->category);
        return view('admin::management.checklist.edit', [
            'question' => $question,
            'cats' => ChecklistCategory::pluck('title', 'id'),
            'parentQuestionsList' => $parentQuestionsList,
            'clients' => Client::pluck('descrip', 'id'),
            'lenders' => UserGroupLender::pluck('lender', 'id'),
            'loanTypes' => LoanType::pluck('descrip', 'id'),
            'appraisalTypes' => $this->typesRepo->getTypesForMultiSelect(),
            'loanReasons' => LoanReason::pluck('descrip', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Checklist $question
     * @param  ChecklistRequest $request
     * @return Response
     */
    public function update(Checklist $question, ChecklistRequest $request)
    {
        $data = $request->all();
        $question = $question->setData($data);
        \DB::beginTransaction();
        try {
            if ($question->save()) {
                $question->removeRelations();
                $question->saveRelations($data);
                \DB::commit();
                return redirect(route('admin.qc.checklist.index'))->
                with('success', 'Question has been successfully saved');
            }
            return redirect(route('admin.qc.checklist.index'))->
            with('error', 'Something went wrong');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
            return redirect(route('admin.qc.checklist.index'))->
            with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Checklist $question)
    {
        \DB::beginTransaction();
        try {
            $question->removeRelations();
            if ($question->delete()) {
                \DB::commit();
                return redirect(route('admin.qc.checklist.index'))
                    ->with('success', 'Successfully deleted question');
            }
            return redirect(route('admin.qc.checklist.index'))
                ->with('error', 'Error during deleting question');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
            return redirect(route('admin.qc.checklist.index'))
                ->with('error', 'Error during deleting question');
        }
    }

    /**
     * @param Checklist $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQuestionStatus(Checklist $question)
    {
        $question->is_active = !$question->is_active;
        if ($question->save()) {
            return redirect(route('admin.qc.checklist.index'))
                ->with('success', 'Question Status Updated');
        }
        return redirect(route('admin.qc.checklist.index'))
            ->with('error', 'Impossible to update status');
    }

    /**
     * @param ChecklistCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategoryStatus(ChecklistCategory $category)
    {
        $category->is_active = !$category->is_active;
        if ($category->save()) {
            return redirect(route('admin.qc.checklist.index'))
                ->with('success', 'Category Status Updated');
        }
        return redirect(route('admin.qc.checklist.index'))
            ->with('error', 'Impossible to update status');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParentQuestions($id)
    {
        $list = $this->checklistRepo->makeParentQuestionsDropdown($id);

        return response()->json([
            'html' => \Form::select('parent_question', $list, null, [
                'id' => 'parent_question',
                'class' => 'form-control',
                'placeholder' => 'Choose parent question'
            ])->toHtml(),
        ]);
    }

    /**
     * @param ChecklistCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCategory(ChecklistCategory $category)
    {
        return view('admin::management.checklist.edit_category', [
            'category' => $category,
        ]);
    }

    /**
     * @param ChecklistCategory $category
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategory(ChecklistCategory $category, CategoryRequest $request)
    {
        $category = $category->setData($request->all());
        if ($category->save()) {
            return redirect(route('admin.qc.checklist.index'))
                ->with('success', 'Category has been successfully updated');
        }
        return redirect(route('admin.qc.checklist.index'))
            ->with('error', 'Something went wrong');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createCategory()
    {
        return view('admin::management.checklist.create_category');
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategory(CategoryRequest $request)
    {
        $category = (new ChecklistCategory())->setData($request->all());
        if ($category->save()) {
            return redirect(route('admin.qc.checklist.index'))
                ->with('success', 'Category has been successfully created');
        }
        return redirect(route('admin.qc.checklist.index'))
            ->with('error', 'Something went wrong');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortQuestions(Request $request)
    {
        $sort = $request->post('sort');
        $sort = collect($sort);
        $sort = $sort->map(function ($item) {
            return str_replace('questions_div_elem_', '', $item);
        });
        foreach ($sort as $key => $value) {
            Checklist::where('id', $value)->update(['ord' => $key]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * @param Client $client
     * @param $activity
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeActivityByClient(Client $client, $activity)
    {
        \DB::beginTransaction();
        try {
            $client->checklists()->update(['is_active' => $activity]);
            \DB::commit();
            return redirect(route('admin.qc.checklist.index'))
                ->with('success', 'Activity has been successfully changed');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
            return redirect(route('admin.qc.checklist.index'))
                ->with('error', 'Something went wrong');
        }
    }

    public function changeActivityByLender(Lender $lender, $activity)
    {
        \DB::beginTransaction();
        try {
            $lender->checklists()->update(['is_active' => $activity]);
            \DB::commit();
            return redirect(route('admin.qc.checklist.index'))
                ->with('success', 'Activity has been successfully changed');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::notice($e->getMessage());
            return redirect(route('admin.qc.checklist.index'))
                ->with('error', 'Something went wrong');
        }
    }
}
