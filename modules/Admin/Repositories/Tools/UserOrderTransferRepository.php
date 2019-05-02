<?php

namespace Modules\Admin\Repositories\Tools;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use DB;
use App\Models\Users\User;
use Modules\Admin\Repositories\Clients\ClientRepository;
use Yajra\DataTables\Datatables;

class UserOrderTransferRepository
{   

	/**
     * Object of UserRepository class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of OrderRepository class
     *
     * @var orderRepo
     */
    private $orderRepo;

    /**
     * Object of ClientRepository class
     *
     * @var clientRepo
     */
    private $clientRepo;

    /**
     * UserRepository constructor.
     *
     * @param User $userRepo
     */
    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->orderRepo = new OrderRepository();
        $this->clientRepo = new ClientRepository();
    }

	public function searchUser($query)
    {

        $users = $this->userRepo->userSerchByEmailOrId($query, $query)->pluck('email');

        return $users;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findUser($data)
    {
    	
    	// Try to find the user by id or email
        if ($data['from_type'] == 'email') {
        	$fromUserData = $this->userRepo->getUserInfoByEmailAddress($data['from_user']);
        } else {
        	$fromUserData = $this->userRepo->getUserInfoById($data['from_user']);
        }

        // Try to find the user by id or email
        if ($data['to_type'] == 'email') {
        	$toUserData = $this->userRepo->getUserInfoByEmailAddress($data['to_user']);
        } else {
        	$toUserData = $this->userRepo->getUserInfoById($data['to_user']);
        }
       
        $response = ['success' => true];

        if (!$fromUserData) {

        	$response = [
        		'success' => false,
        		'message' => 'Sorry, We could not find a user with based on the input entered under From User ID / Email.'
        	];
        }

        if (!$response['success']) {
        	return $response;
        } 

        if (!$toUserData) {

        	$response = [
        		'success' => false,
        		'message' => 'Sorry, We could not find a user with based on the input entered under To User ID / Email.'
        	];
        }

        if (!$response['success']) {
        	return $response;
        } 

        // make sure they are the same type
        if ($toUserData->user_type != $fromUserData->user_type) {

        	$response = [
        		'success' => false,
        		'message' => 'Sorry, Both users must be of the same user type.'
        	];
        }

        // make sure they are the same type
        if ($toUserData->id == $fromUserData->id) {

            $response = [
                'success' => false,
                'message' => 'Sorry, Users can not be same'
            ];
        }

        if (!$response['success']) {
        	return $response;
        } 

        // Get how many orders from user has
        $orders = $this->orderRepo->getUserTransferableOrders($fromUserData->user_type, $fromUserData->id);

        if (!$orders['success']) {
        	$response  = [
        		'success' => false,
        		'message' => 'Sorry, There are no orders to transfer.'
        	];
        } else if ($orders['orders']->count() == 0) {
        	$response  = [
        		'success' => false,
        		'message' => 'Sorry, There are no orders to transfer.'
        	];
        	
        } else {
        	$orders = $orders['orders'];
        }

        if (!$response['success']) {
        	return $response;
        } 

        return ['success' => true, 'from_user' => $fromUserData, 'to_user' => $toUserData , 'orders' => $orders];
    }

    /**
     * do users transfer 
     * 
     *  @param object $fromUser
     *  @param object $toUser
     *  @param array $orders
     *  @return mixed
     */
    public function userOrderTransfer($fromUser, $toUser, $orders)
    {

		// add transfer log record
		$transferLogId = DB::table('user_transfer_log')->insertGetId(
			    ['fromid' => $fromUser->id, 'toid' => $toUser->id, 'created_date' => time(), 'created_by' => admin()->id]
			);
		$groupData = null;

		if($fromUser->user_type == User::USER_TYPE_CLIENT) {
			$groupData = $this->clientRepo->teams($toUser->groupid);
		}
        
		$count = 0;
		
		foreach($orders as $order) {

			if($fromUser->user_type == User::USER_TYPE_CLIENT) {

				$update = ['orderedby' => $toUser->id];

				if($groupData) {
					$update['groupid'] = $groupData->id;
				}

				$this->orderRepo->update($order->id, $update);

			} elseif ($fromUser->user_type == User::USER_TYPE_APPRAISER) {

				$this->orderRepo->update($order->id, ['acceptedby' => $toUser->id]);
			}

			$count++;

			DB::table('user_transfer_log_record')->insert([
			    ['order_id' => $order->id, 'log_id' => $transferLogId]
			]);
		}

		// If this is an appariser transfer then update appraiser payments
		if($fromUser->user_type == User::USER_TYPE_APPRAISER) {

			DB::table('appraiser_payments')->where('apprid', $fromUser->id)
				->update([
			    	['apprid' => $toUser->id]
			]);
		}

		// Update count
		DB::table('user_transfer_log')->where('id', $transferLogId)->update(['records' => $count]);


		return ['success' => true, 'count' => $count, 'transfer-log-id' => $transferLogId];
    }

    public function getTransferedOrders($orderTransferLogId)
    {
    	$transfersLogRecords = DB::table("user_transfer_log_record")->where('log_id', $orderTransferLogId)->get()->pluck('order_id');
        $orders = DB::table('appr_order')->whereIn('id', $transfersLogRecords)->get();
    	return $orders;
    }



    /**
     * get orders for dataTable
     *
     * @return array $customPagesDataTables
     */
    public function ordersDataTables()
    {
        $transferedOrders = DB::table('user_transfer_log')->get();
        
        $transferedOrdersDataTables = Datatables::of($transferedOrders)
        		->editColumn('options', function ($transferedOrder) {
                    return view('admin::tools.user-order-transfers.partials._options', ['transferedOrderId' => $transferedOrder->id])->render();
                })
                ->editColumn('id', function ($transferedOrder) {
                    return $transferedOrder->id;
                })
                ->editColumn('from_user', function ($transferedOrder) {
                	$fromUser = $this->userRepo->getUserInfoById($transferedOrder->fromid);
                    return $fromUser->userData->firstname.' '.$fromUser->userData->lastname;
                })
                ->editColumn('to_user', function ($transferedOrder) {
                    $toUser = $this->userRepo->getUserInfoById($transferedOrder->toid);
                    return $toUser->userData->firstname.' '.$toUser->userData->lastname;
                })
                ->editColumn('created_date', function ($transferedOrder) {
                    return date('m/d/Y H:i', $transferedOrder->created_date);
                })
                ->editColumn('proccessed_by', function ($transferedOrder) {
                    $user = $this->userRepo->getUserInfoById($transferedOrder->created_by);
                    return $user->userData->firstname.' '.$user->userData->lastname;
                })
                ->editColumn('order_count', function ($transferedOrder) {
                    return $transferedOrder->records;
                })
                ->rawColumns(['options'])
                ->make(true);
                
        return $transferedOrdersDataTables;
    }
}