<?php

namespace Modules\Admin\Http\Controllers\Ticket;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Ticket\RuleRequest;
use Modules\Admin\Services\Ticket\TicketRuleService;
use Modules\Admin\Services\Ticket\AssignmentService;
use Modules\Admin\Services\Ticket\TemplateService;
use Yajra\Datatables\Datatables;
use Modules\Admin\Contracts\Ticket\CategoryContract;
use Modules\Admin\Contracts\Ticket\StatusContract;
use App\Models\Ticket\Rule;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\MultiMod;
use App\Models\Ticket\RuleAction;
use App\Models\Ticket\RuleCondition;

class RuleController extends AdminBaseController
{
    protected $rule;

    protected $statusRepo;
    protected $categoryRepo;
    protected $template;
    protected $assignment;

    /**
     * RuleController constructor.
     * @param StatusContract $status
     * @param CategoryContract $category
     * @param TicketRuleService $rule
     * @param AssignmentService $assignment
     * @param TemplateService $template
     */
    public function __construct(
        StatusContract $status,
        CategoryContract $category,
        TicketRuleService $rule,
        AssignmentService $assignment,
        TemplateService $template
    )
    {
        $this->rule = $rule;

        $this->statusRepo = $status;
        $this->categoryRepo = $category;

        $this->assignment = $assignment;
        $this->template = $template;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin::ticket.rule.index');
    }

    /**
     * Process Datatables after AJAX call
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $rules = Rule::all();

            return Datatables::of($rules)
                ->editColumn('is_active', function ($r) {
                    return $r->is_active ? 'Yes' : 'No';
                })
                ->editColumn('created_by', function ($r) {
                    return $r->user->fullname;
                })
                ->editColumn('created_date', function ($r) {
                    return date('m/d/Y G:i A', $r->created_date);
                })
                ->addColumn('action', function ($r) {
                    return view('admin::ticket.rule.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param RuleRequest $request
     * @return mixed
     */
    public function create(RuleRequest $request)
    {
        if ($request->isMethod('post')) {
            $this->rule->create($request);

            return redirect()->route('admin.ticket.rule')
                ->with('success', 'Record Saved.');
        }

        return view('admin::ticket.rule.form', $this->getFormFields([
            'title' => 'Create Record',
            'action' => 'create',
            'row' => new Rule,
            'ruleAction' => new RuleAction
        ]));
    }

    /**
     * @param RuleRequest $request
     * @param Rule $rule
     * @return mixed
     */
    public function update(RuleRequest $request, Rule $rule)
    {
        if ($request->isMethod('put')) {
            $this->rule->update($request, $rule);

            return redirect()->route('admin.ticket.rule')
                ->with('success', 'Record Saved.');
        }

        $ruleAction = RuleAction::where('rule_id', $rule->id)->first();

        // Render
        return view('admin::ticket.rule.form', $this->getFormFields([
            'title' => 'Edit Record',
            'action' => 'update',
            'row' => $rule,
            'ruleAction' => $ruleAction
        ]));
    }

    /**
     * @param Request $request
     * @param Rule $rule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, Rule $rule)
    {
        $this->rule->delete($rule);

        return redirect()->route('admin.ticket.rule')
            ->with('success', 'Record Deleted.');
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getFormFields($data)
    {
        $conditions = RuleCondition::where('rule_id', $data['row']->id)->get();
        $assignments = $this->assignment->getAssignmentList();
        $categories = $this->categoryRepo->getCategories();
        $conditionKeys = RuleCondition::getConditionKeys();
        $conditionTypes = RuleCondition::getConditionMatchTypes();

        $conditionRowTemplate = view('admin::ticket.rule.partials._condition', [
            'id' => '{id}',
            'key' => '{key}',
            'type' => '{type}',
            'value' => '{value}',
            'conditionKeys' => $conditionKeys,
            'conditionTypes' => $conditionTypes,
            'categories' => $categories,
        ])->render();

        $emailTemplate = view('admin::ticket.manager.templates._email', [
            'phone' => config('app.phone_number'),
        ])->render();

        return [
            'title' => $data['title'],
            'action' => $data['action'],
            'row' => $data['row'],
            'ruleAction' => $data['ruleAction'],
            'conditions' => $conditions,
            'assignments' => $assignments,
            'emailTemplate' => str_replace(["\r", "\n"], '', $emailTemplate),
            'conditionRowTemplate' => str_replace(["\r", "\n"], '', $conditionRowTemplate),
            'conditionKeysMatched' => RuleCondition::getConditionKeysMatched(),
            'conditionTypes' => $conditionTypes,
            'conditionKeys' => $conditionKeys,
            'matchTypes' => Rule::getMatchTypes(),
            'emailTemplates' => $this->template->getTemplateList(),
            'statuses' => $this->statusRepo->getStatuses(),
            'categories' => $categories,
            'priorities' => Ticket::getPriorityList(),
            'multiMods' => MultiMod::where('is_active', 1)->orderBy('title', 'asc')->get(),
        ];
    }
}