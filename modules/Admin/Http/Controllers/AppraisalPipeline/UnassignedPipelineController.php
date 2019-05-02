<?php

namespace Modules\Admin\Http\Controllers\AppraisalPipeline;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\Customizations\Status;
use App\Models\Clients\Client;
use App\Models\Appraisal\Order;
use App\Models\AppraisalPipeline\EscalateLog;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use App\Models\Customizations\LoanReason;
use App\Models\Tools\Setting;

class UnassignedPipelineController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $teams = AdminTeam::getAdminTeams();
        $clients = Client::getAllClients();
        $states = getStates();
        $timeZones = getRegions();
        $reasons = LoanReason::getReasons();

        return view('admin::appraisal-pipeline.unassigned-pipeline.index',
                compact(
                        'teams',
                        'clients',
                        'states',
                        'timeZones',
                        'reasons'
                    )
            );
    }

    /**
    * Update Worked
    */
    public function markAsReviewed(Request $request)
    {
        $id = $request->id;
        $order = Order::getOrderById($id);
        $reviewed = $order->is_escalated_worked_today ? 0 : 1;
        Order::where('id', $id)->update(['is_escalated_worked_today' => $reviewed]);
        return redirect()->back();
    }

    /**
    * Update Priority
    */
    public function markPriority(Request $request)
    {
        $data = $request->all();
        Order::where('id', $data['id'])->update(['unassigned_priority' => $data['priority']]);
        return redirect()->back();
    }

    /**
     * Gather data for index page and datatables
     */
    public function data(
            Request $request,
            OrderRepository $orderRepository
        )
    {
        $inputs = $request->filters;
        $orders = $orderRepository->getFilteredData($inputs);
        $self = $this;
        return Datatables::of($orders)
            ->editColumn('title', function ($r) use ($self){
                return "<a href='#'>$r->id</a>";
            })
            ->editColumn('address', function ($r) use ($self){
                return $self->getOrderAddress($r);
            })
            ->editColumn('assigned_date', function ($r){
                $icon = $r->unassigned_date ? '<i class="fa fa-circle" aria-hidden="true"></i>' : '';
                $date = !is_null($r->assigned_date) ? date('m/d/Y g:i A', $r->assigned_date) : '';
                return $date .' '. $icon;
            })
            ->editColumn('worked', function ($r){
                return $r->is_escalated_worked_today ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-eye" aria-hidden="true"></i>';
            })
            ->editColumn('tickets', function ($r){
                return $r->tickets->where('closed_date', 0)->where('closedid', 0)->count();
            })
            ->addColumn('actions', function ($r) {
                return view('admin::appraisal-pipeline.unassigned-pipeline.partials._options', ['row' => $r]);
            })
            ->setRowClass(function ($r) {
                $class = '';

                $class = $r->is_rush ? $class . "row-is-rush success" : $class . '';

                if($r['due_date'] && in_array($r['status'], array(2, 3, 4, 5, 8, 15, 19))) {
                    if(date('m/d/Y', $r['due_date']) == date('m/d/Y')) {
                        $class = $class . ' due-date-today';
                    } elseif($r['due_date'] < time()) {
                        $class = $class . ' due-date-past';
                    }
                }

                $setting = Setting::getSetting('appr_is_order_purhcase');
                $row = $setting ? explode(',', $setting) : [];
                $class = in_array($r->loanpurpose, $row) ? $class . ' order-purchased' : $class . '';

                if($r->is_unassigned && !$r->acceptedby) {
                    $class = $class . ' order-reassign';
                }

                if(!$r->is_assigned && $r->invites && count($r->invites)) {
                    $class = $class . ' order-has-invites';
                }

                return $class;
            })
            ->make(true);
    }

    /**
    * Get order Address
    */
    private function getOrderAddress($order)
    {
        $address = ucwords(trim(strtolower($order->propaddress1) . ' ' . strtolower($order->propaddress2))) . ', ' . ucwords(strtolower($order->propcity)) . ', ' . strtoupper($order->propstate);
        return trim(trim($address), ',');
    }
}
