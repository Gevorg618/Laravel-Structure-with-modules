<?php

namespace Modules\Admin\Http\Controllers\AppraisalPipeline;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Management\AdminTeamsManager\AdminTeamMember;
use App\Models\Management\AdminGroup\AdminPermissionCategory;
use App\Models\Appraisal\{ OrderLog, Order, Status };
use App\Models\Clients\Client;
use Modules\Admin\Services\Ticket\TicketModerationService;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Carbon\Carbon;
use Session;

class DelayedOrdersPipelineController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        return view('admin::appraisal-pipeline.delayed-orders-pipeline.index');
    }

    /**
    * Destroy
    * @return void
    */
    public function destroy($id, TicketModerationService $ticketModerationService)
    {
        $order = Order::getApprOrderById($id);
        if(!$order) {
            Session::flash('error', 'Sorry, That file was not found.');
            return redirect()->back();
        }
        Order::where('id', $id)->update([
            'is_delayed' => 0,
            'is_delayed_complete' => 1
        ]);
        $message = sprintf("Order Delay Removed.");
        // ADD LOG
        try {
            $ticketModerationService->addNewLogEntry(['orderId' => $id, 'info' => $message]);
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }

        Session::flash('success', $message);
        return redirect()->back();
    }

    /**
     * Gather data for index page and datatables
     */
    public function data(
            AdminPermissionCategory $adminPermissionCategory,
            OrderRepository $orderRepository
        )
    {
        $orders = [];
        $self = $this;
        $rows = $orderRepository->getDelayedData();
        $adminTeamId = AdminTeamMember::getAdminUserTeamId(getUserId());
        $checkPermission = checkPermission($adminPermissionCategory, 'can_view_all_delayed_files');

        if($rows) {
            if($checkPermission) {
                $orders = $rows;
            } else {
                foreach($rows as $row) {
                    if(!is_null($row->team_id) && $adminTeamId == $row->team_id) {
                        $orders[] = $row;
                    }
                }
            }
        }
        if($orders) {
            foreach($rows as $order) {
                if ($order->lastLog->isNotEmpty()) {
                    $order->last_log = Carbon::parse($order->lastLog->first()->dts)->format('m/d/Y H:i');
                } else {
                    $order->last_log = '--';
                }
            }
        }

        return Datatables::of($orders)
            ->editColumn('ordereddate', function ($r) {
                return Carbon::parse($r->ordereddate)->format('m/d/Y H:i');
            })
            ->editColumn('client', function ($r){
                $row = $r->apprClient;
                return $row->isNotEmpty() ? $row[0]->company : 'N/A';
            })
            ->editColumn('address', function ($r) use ($self){
                return '<a href="">'. $self->getOrderAddress($r) .'</a>';
            })
            ->editColumn('status', function ($r) {
                $row = $r->apprStatus;
                return $row->isNotEmpty() ? $row[0]->descrip : 'N/A';
            })
            ->editColumn('last_log', function ($r) {
                return $r->last_log != '--' ? Carbon::parse($r->last_log)->format('m/d/Y H:i') : $r->last_log;
            })
            ->addColumn('action', function ($r) {
                return view('admin::appraisal-pipeline.delayed-orders-pipeline.partials._options', ['row' => $r]);
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
