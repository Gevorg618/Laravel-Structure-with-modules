<?php

namespace Modules\Admin\Http\Controllers\PostCompletionPipelines;

use Carbon\Carbon;
use App\Models\Clients\Client;
use App\Models\Customizations\Status;
use Yajra\Datatables\Datatables;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Http\Controllers\AdminBaseController;

class ReconsiderationPipelineController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $underReviewView = $this->underReviewView();
        $waitingForApprovalView = $this->waitingForApprovalView();

        return view('admin::post-completion-pipelines.reconsideration-pipeline.index',
            compact(
                'underReviewView',
                'waitingForApprovalView'
            )
        );
    }

    /**
    * Under Review Tab
    * @return view
    */
    public function underReviewView()
    {
        return view('admin::post-completion-pipelines.reconsideration-pipeline.partials._under_review');
    }

    /**
    * Gather data for Under Review tab and datatables
    */
    public function underReviewData(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->getReconsiderationPipeline('UnderReview');
        $self = $this;

        return Datatables::of($orders)
            ->editColumn('order', function ($r){
                return $r->id .'<br />'. Carbon::parse($r->ordereddate)->format('m/d/Y H:i');
            })
            ->editColumn('client', function ($r){
                return $r->apprClient && $r->apprClient->first() ? $r->apprClient->first()->company: 'N/A';
            })
            ->editColumn('address', function ($r) use ($self){
                return $self->getOrderAddress($r);
            })            
            ->editColumn('status', function ($r){
                return $r->apprStatus ? $r->apprStatus->first()->descrip: 'N/A';
            })
            ->make(true);
    }

    /**
    * Waiting For Approval Tab
    * @return view
    */
    public function waitingForApprovalView()
    {
        return view('admin::post-completion-pipelines.reconsideration-pipeline.partials._waiting_for_approval');
    }

    /**
    * Gather data for Waiting For Approval tab and datatables
    */
    public function waitingForApprovalData(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->getReconsiderationPipeline('WaitingForApproval');
        $self = $this;

        return Datatables::of($orders)
            ->editColumn('order', function ($r){
                return $r->id .'<br />'. Carbon::parse($r->ordereddate)->format('m/d/Y H:i');
            })
            ->editColumn('client', function ($r){
                return $r->apprClient && $r->apprClient->first()? $r->apprClient->first()->company: 'N/A';
            })
            ->editColumn('address', function ($r) use ($self){
                return $self->getOrderAddress($r);
            })
            ->editColumn('status', function ($r){
                return $r->apprStatus ? $r->apprStatus->first()->descrip: 'N/A';
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
