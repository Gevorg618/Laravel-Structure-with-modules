<?php

namespace Modules\Admin\Http\Controllers\AppraisalPipeline;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\Customizations\Status;
use App\Models\Clients\Client;
use App\Models\Appraisal\Order;
use Modules\Admin\Repositories\Ticket\OrderRepository;

class PurchasePipelineController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $teams = AdminTeam::getAdminTeams();
        $statuses = Status::getStatuses();
        $clients = Client::getAllClients();

        return view('admin::appraisal-pipeline.purchase-pipeline.index',
                compact(
                        'teams',
                        'statuses',
                        'clients'
                    )
            );
    }

    /**
    * Index
    *@param Request
    * @return void
    */
    public function markAsReviewed(Request $request)
    {
        $id = $request->id;
        $order = Order::getOrderById($id);
        $reviewed = $order->is_contract_reviewed ? 0 : 1;
        $update = ['is_contract_reviewed' => $reviewed];
        if($reviewed) {
            $update['is_contract_requested'] = 0;
        }
        Order::where('id', $id)->update($update);
    }

    /**
    * Index
    *@param Request
    * @return void
    */
    public function markAsWorked(Request $request)
    {
        $id = $request->id;
        $order = Order::getOrderById($id);
        $reviewed = $order->is_purchase_worked_today ? 0 : 1;
        Order::where('id', $id)->update(['is_purchase_worked_today' => $reviewed]);
    }

    /**
    * Index
    *@param Request
    * @return void
    */
    public function markAsRequested(Request $request)
    {
        $id = $request->id;
        $order = Order::getOrderById($id);
        $reviewed = $order->is_contract_requested ? 0 : 1;
        Order::where('id', $id)->update(['is_contract_requested' => $reviewed]);
    }

    /**
     * Gather data for index page and datatables
     */
    public function data(
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
                return $r->is_purchase_worked_today ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-eye" aria-hidden="true"></i>';
            })
            ->editColumn('construction', function ($r){
                return $r->is_new_construction ? 'Yes' : 'No';
            })
            ->editColumn('contract', function ($r) use ($self){
                return $self->contract($r);
            })
            ->addColumn('action', function ($r) {
                return view('admin::appraisal-pipeline.purchase-pipeline.partials._options', ['row' => $r]);
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
    * Get contract Content
    */
    private function contract($row)
    {
        $contract = '<i class="fa fa-times" aria-hidden="true"></i>';
        if($row->document_id) {
            $contract = '<a href="#" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>';
        }
        if($row->is_contract_requested) {
            $contract = '<i class="fa fa-spinner fa-spin fa-fw" aria-hidden="true"></i>';
        }
        if($row->is_contract_reviewed) {
            $contract = '<i class="fa fa-check" aria-hidden="true"></i>';
        }
        return $contract;
    }
}
