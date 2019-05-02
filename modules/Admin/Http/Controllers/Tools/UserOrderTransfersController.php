<?php 

namespace Modules\Admin\Http\Controllers\Tools;

use Modules\Admin\Http\Controllers\AdminBaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Repositories\Tools\UserOrderTransferRepository;


class UserOrderTransfersController extends AdminBaseController 
{

    /**
     * Object of UserOrderTransferRepository class
     *
     * @var userOrderTransferRepo
     */
    private $userOrderTransferRepo;

    /**
     * Object of UserRepository class
     *
     * @var userRepository
     */
    private $userRepository;

    /**
     * Create a new instance of UserOrderTransfersController class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userOrderTransferRepo = new UserOrderTransferRepository();
        $this->userRepository = new UserRepository();
    }

    /**
     * GET /admin/tools/custom-pages-manager/index
     *
     * Custom Pages index page
     *
     * @return view
     */
    public function index()
    {
        return view('admin::tools.user-order-transfers.index', compact('usersData'));
    }

    /**
     * GET /admin/tools/custom-pages-manager/index
     *
     * load user index page
     *
     * @return view
     */
    public function loadInfo(Request $request)
    {
        if ($request->ajax()) {

            $requestData = $request->only('from_user',  'from_type', 'to_user', 'to_type');
            $user = $this->userOrderTransferRepo->findUser($requestData);

            if ($user['success']) {

                $fromUser = $user['from_user'];
                $toUser = $user['to_user'];
                $toType = $requestData['to_type'];
                $fromType = $requestData['from_type'];

                $ordersCount = $user['orders']->count();
                $html = view('admin::tools.user-order-transfers.partials._user-info', compact('fromUser', 'toUser', 'ordersCount', 'fromType', 'toType') )->render();
                $user['html'] = $html;
            
            }

            return response()->json($user);
        }
    } 

    /**
     * POST /admin/tools/custom-pages-manager/transfer-order
     *
     * load user index page
     *
     * @return view
     */
    public function transfer(Request $request)
    {

        $requestData = $request->only('from_user',  'from_type', 'to_user', 'to_type');
        
        $requestData['to_type'] = 'id';
        $requestData['from_type'] = 'id';

        $user = $this->userOrderTransferRepo->findUser($requestData);

        if ($user['success']) {

            $fromUser = $user['from_user'];
            $toUser = $user['to_user'];
            $orders = $user['orders'];

            $transferedOrder = $this->userOrderTransferRepo->userOrderTransfer($fromUser, $toUser, $orders);
            
            if ($transferedOrder['success']) {

                Session::flash('success', 'Orders was successfuly transfered!');
                Session::flash('transfer-log-id', $transferedOrder['transfer-log-id']);

                return redirect()->route('admin.tools.user-order-transfers.index');
            }

        } else {

            Session::flash('error', $user['message']);
            return redirect()->route('admin.tools.user-order-transfers.index');

        }
    }

    /**
     * GET /admin/tools/custom-pages-manager/transfer-order
     *
     * get transfered order by transfer order log id
     *
     * @param  int $orderTransferLogId
     * @return json
     */
    public function transferedOrders($orderTransferLogId)
    {
       $orders =  $this->userOrderTransferRepo->getTransferedOrders($orderTransferLogId);
       $html = view('admin::tools.user-order-transfers.partials._orders', compact('orders') )->render();
       return response()->json($html);
    }

    /**
     * GET /admin/tools/custom-pages-manager/orders
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $orders
     */
    public function ordersData(Request $request)
    {
        if ($request->ajax()) {

            $transferedOrdersDataTables = $this->userOrderTransferRepo->ordersDataTables();

            return $transferedOrdersDataTables;
        }
    }

    /**
     * GET /admin/tools/custom-pages-manager/search
     *
     * method get data for showing with ajax datatable 
     *
     * @param Request $request
     *
     * @return array $orders
     */
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->userOrderTransferRepo->searchUser($request->get('query'));
            return response()->json($users);
        }
    }
}