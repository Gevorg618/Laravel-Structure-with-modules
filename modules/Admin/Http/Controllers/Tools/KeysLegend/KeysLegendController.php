<?php

namespace Modules\Admin\Http\Controllers\Tools\KeysLegend;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Appraisal\Order;
use App\Models\Users\User;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use App\Models\Customizations\AMCLicense;
use App\Models\Appraisal\QC\DataQuestion;
use App\Models\Tiger\Amc;

class KeysLegendController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index()
    {
        $cols = 2;
        $order = Order::orderLegend();
        $orderLegendView = $this->orderLegend($cols, $order);
        $userLegendView = $this->userLegend($cols, $order->orderedby);
        $groupLegendView = $this->groupLegend($cols, $order->groupid);
        $lenderLegendView = $this->lenderLegend($cols, $order->lender_id);
        $apprLegendView = $this->apprLegend($cols, $order);
        $amcRegLegendView = $this->amcRegLegend($cols, $order->id);
        $apprDataCollView = $this->apprDataColl($cols, $order->id);

        return view('admin::tools.keys_legend.index',
            compact(
                    'orderLegendView',
                    'userLegendView',
                    'groupLegendView',
                    'lenderLegendView',
                    'apprLegendView',
                    'amcRegLegendView',
                    'apprDataCollView'
                )
        );
    }

    /**
    * Order Legend Tab
    * @return view
    */
    private function orderLegend($cols, $order)
    {
        return view('admin::tools.keys_legend.partials._order_legend',
                compact('order', 'cols')
            );
    }

    /**
    * User Legend Tab
    * @return view
    */
    private function userLegend($cols, $order_id)
    {
        $row = userInfo($order_id, true);
        $userLegend = !empty($row) ? $row->toArray() : [];

        return view('admin::tools.keys_legend.partials._user_legend',
                compact('userLegend', 'cols')
            );
    }

    /**
    * Group Legend Tab
    * @return view
    */
    private function groupLegend($cols, $order_id)
    {
        $row = Client::getGroupData($order_id);
        $groupLegend = !empty($row) ? $row->toArray() : [];

        return view('admin::tools.keys_legend.partials._group_legend',
                compact('groupLegend', 'cols')
            );
    }

    /**
    * Lender Legend Tab
    * @return view
    */
    private function lenderLegend($cols, $order_id)
    {
        $row = UserGroupLender::getLenderRecord($order_id);
        $lenderLegend = !empty($row) ? $row->toArray() : [];

        return view('admin::tools.keys_legend.partials._lender_legend',
                compact('lenderLegend', 'cols')
            );
    }

    /**
    * Apprasier Legend Tab
    * @return view
    */
    private function apprLegend($cols, $order)
    {
        $apprLegend = false;
        $row = $this->getApprLegend($order);
        if($row) {
            $apprLegend = array_merge($row->toArray(), $row->userData->toArray());
            unset($apprLegend['user_data']);
        }
        return view('admin::tools.keys_legend.partials._appr_legend',
                compact('apprLegend', 'cols')
            );
    }

    /**
    * AMC Registration Legend Tab
    * @return view
    */
    private function amcRegLegend($cols, $order_id)
    {
        $row = AMCLicense::getAMCRegistrationNumber('CA');
        $amcLicenses = !empty($row) ? $row->toArray() : [];

        return view('admin::tools.keys_legend.partials._amc_reg_legend',
                compact('amcLicenses', 'cols')
            );
    }

    /**
    * Appraisal QC Data Collection Tab
    * @return view
    */
    private function apprDataColl($cols, $order_id)
    {
        $row = DataQuestion::getAllQuestion();
        $apprDataColl = !empty($row) ? $row->toArray() : [];

        return view('admin::tools.keys_legend.partials._appr_data_coll',
                compact('apprDataColl', 'cols')
            );
    }

    /**
    * Appr Legend
    */
    public function getApprLegend($order) {
        // Make sure it's assigned
        if(!$order->is_assigned) {
            return false;
        }
        if($order->acceptedby) {
            $vendor = User::getUserAllData($order->acceptedby);
            return $vendor;
        }
        return false;
    }
}
