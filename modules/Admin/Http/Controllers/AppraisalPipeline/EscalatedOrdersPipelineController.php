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

class EscalatedOrdersPipelineController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $teams = AdminTeam::getAdminTeams();
        $statuses = Status::getStatuses();
        $clients = Client::getAllClients();

        return view('admin::appraisal-pipeline.escalated-orders-pipeline.index',
                compact(
                        'teams',
                        'statuses',
                        'clients'
                    )
            );
    }

    /**
    * Update Worked
    */
    public function updateData(Request $request)
    {
        $id = $request->id;
        $order = Order::getOrderById($id);
        $reviewed = $order->is_escalated_worked_today ? 0 : 1;
        Order::where('id', $id)->update(['is_escalated_worked_today' => $reviewed]);
        return redirect()->back();
    }

    /**
     * Gather data for index page and datatables
     */
    public function filterData(
            Request $request,
            OrderRepository $orderRepository
        )
    {
        $data = $request->filters;
        $orders = $orderRepository->getFilteredData($data);
        $self = $this;

        return Datatables::of($orders)
            ->editColumn('id', function ($r) use ($self){
                return "<a href='#'>$r->id</a>";
            })
            ->editColumn('address', function ($r) use ($self){
                return $self->getOrderAddress($r);
            })
            ->editColumn('worked', function ($r){
                return $r->is_escalated_worked_today ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-eye" aria-hidden="true"></i>';
            })
            ->editColumn('content', function ($r) use ($self){
                return $self->getContent($r->id);
            })
            ->editColumn('content', function ($r) use ($self){
                return $self->getContent($r->id);
            })
            ->addColumn('action', function ($r) {
                return view('admin::appraisal-pipeline.escalated-orders-pipeline.partials._options', ['row' => $r]);
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

    /**
    * Get order Content
    */
    private function getContent($id)
    {
        $content = EscalateLog::getContentById($id);
        return is_null($content) ? '' : $content->content;
    }

}
