<?php

namespace Modules\Admin\Http\Controllers\Ticket;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Ticket\ModerationRequest;
use Yajra\Datatables\Datatables;
use Modules\Admin\Services\Ticket\TicketModerationService;
use Modules\Admin\Services\Ticket\AssignmentService;
use Modules\Admin\Services\Ticket\TemplateService;
use Modules\Admin\Contracts\Ticket\CategoryContract;
use Modules\Admin\Contracts\Ticket\StatusContract;
use Modules\Admin\Contracts\UserContract;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\MultiMod;

class ModerationController extends AdminBaseController
{
    protected $moderation;

    protected $statusRepo;
    protected $categoryRepo;
    protected $template;
    protected $assignment;

    /**
     * ModerationController constructor.
     * @param StatusContract $status
     * @param CategoryContract $category
     * @param TicketModerationService $moderation
     * @param AssignmentService $assignment
     * @param TemplateService $template
     */
    public function __construct(
        StatusContract $status,
        CategoryContract $category,
        TicketModerationService $moderation,
        AssignmentService $assignment,
        TemplateService $template
    )
    {
        $this->assignment = $assignment;
        $this->template = $template;
        $this->moderation = $moderation;

        $this->statusRepo = $status;
        $this->categoryRepo = $category;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin::ticket.moderation.index');
    }

    /**
     * Process Datatables after AJAX call
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $rows = MultiMod::orderBy('title', 'asc');

            return Datatables::of($rows)
                ->editColumn('is_active', function ($r) {
                    return $r->is_active ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::ticket.moderation.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * @param ModerationRequest $request
     * @return mixed
     */
    public function create(ModerationRequest $request)
    {
        if ($request->isMethod('post')) {
            $this->moderation->create($request);

            return redirect()->route('admin.ticket.moderation')
                ->with('success', 'Record Saved.');
        }

        return view('admin::ticket.moderation.form', $this->getFormFields([
            'title' => 'Create Record',
            'action' => 'create',
            'row' => new MultiMod(),
        ]));
    }

    /**
     * @param ModerationRequest $request
     * @param MultiMod $mod
     * @return mixed
     */
    public function update(ModerationRequest $request, MultiMod $mod)
    {
        if ($request->isMethod('put')) {
            $this->moderation->update($request, $mod);

            return redirect()->route('admin.ticket.moderation')
                ->with('success', 'Record Saved.');
        }

        // Render
        return view('admin::ticket.moderation.form', $this->getFormFields([
            'title' => 'Edit Record',
            'action' => 'update',
            'row' => $mod,
        ]));
    }

    /**
     * @param Request $request
     * @param MultiMod $mod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, MultiMod $mod)
    {
        $this->moderation->delete($mod);

        return redirect()->route('admin.ticket.moderation')
            ->with('success', 'Record Deleted.');
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getFormFields($data)
    {
        $emailTemplate = view('admin::ticket.manager.templates._email', [
            'phone' => config('app.phone_number'),
        ])->render();

        return [
            'title' => $data['title'],
            'action' => $data['action'],
            'row' => $data['row'],
            'assignments' => $this->assignment->getAssignmentList(),
            'emailTemplate' => str_replace(["\r", "\n"], '', $emailTemplate),
            'emailTemplates' => $this->template->getTemplateList(),
            'statuses' => $this->statusRepo->getStatuses(),
            'categories' => $this->categoryRepo->getCategories(),
            'priorities' => Ticket::getPriorityList(),
            'multiMods' => MultiMod::where('is_active', 1)->orderBy('title', 'asc')->get(),
        ];
    }
}