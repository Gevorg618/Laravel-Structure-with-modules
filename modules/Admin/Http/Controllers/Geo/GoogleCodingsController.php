<?php

namespace Modules\Admin\Http\Controllers\Geo;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Ticket\OrderRepository;

class GoogleCodingsController extends AdminBaseController
{   
    /**
     * Object of OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;
    
    /**
     * Create a new instance of GoogleCodingsController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {   
        $ordersCount = $this->orderRepo->googleGeoCodeOverQueryLimit()->count();
        return view('admin::geo.google-geo-coding.index', compact('ordersCount'));
    }

    /**
     * GET /admin/tools/custom-pages-manager/data
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $customPages
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {

            $orders = $this->orderRepo->googleGeoCodeOrderDataTable();

            return $orders;
        }
    }

    /**
     * GET /admin/tools/custom-pages-manager/data
     *
     * method get data for showing with ajax datatable 
     *
     * @param integer $id order id
     * @param Request $request
     * @return json
     */
    public function geoCode($id, Request $request)
    {
        if ($request->ajax()) {

            // get order by id
            $order = $this->orderRepo->getOrder($id);

            if ($order) {

                // get order lat & long
                $updateApprOrderLatLong = $this->orderRepo->updateApprOrderLatLong($order);

                return response()->json($updateApprOrderLatLong);
            } else {

                // array response if not found order
                $response = [
                    'success' => false,
                    'message' => "Order not fount"
                ];
                return response()->json($response);
            }

            
        }
    }

}
