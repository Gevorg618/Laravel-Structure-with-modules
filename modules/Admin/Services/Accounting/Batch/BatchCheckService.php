<?php

namespace Modules\Admin\Services\Accounting\Batch;


use App\Models\Appraisal\ApprFDPayment;
use App\Models\Appraisal\ApprFdProfile;
use App\Models\Accounting\CheckReceived;
use App\Models\Accounting\CimCheckPayment;
use App\Models\Appraisal\Order;
use App\Models\Tools\Setting;
use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;
use Modules\Admin\Services\Ticket\TicketModerationService;

/**
 * Class BatchCheckService
 * @package Modules\Admin\Services
 */
class BatchCheckService extends BatchService
{
    const CHECK = 'CHECK';
    const CASHIER_CHECK = 'CASHIER_CHECK';
    const MONEY_ORDER = 'MONEY_ORDER';
    const OTHER = 'OTHER';
    const PAYMENT_PENDING = 7;
    protected $orderRepository;
    protected $ticketService;

    /**
     * BatchCheckService constructor.
     * @param OrderRepository $orderRepository
     * @param TicketModerationService $ticketService
     */
    public function __construct(
        OrderRepository $orderRepository,
        TicketModerationService $ticketService
    )
    {
        $this->orderRepository = $orderRepository;
        $this->ticketService = $ticketService;
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @param array $clients
     * @return array
     */
    public function getOrders($from, $to, $type, $clients = [])
    {
        $orders = $this->orderRepository->getBatchCheckData($from, $to, $type, $clients);
        $rows = [];

        $totals = [
            'invoicedue' => 0,
            'paid_amount' => 0
        ];

        if(count($orders)) {
            $statuses = [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_CANCELLED
            ];
            $paymentStatuses = [
                Order::STATUS_UNPAID,
                Order::STATUS_BALANCE_DUE,
                Order::STATUS_INVOICED,
                Order::STATUS_COD
            ];
            foreach($orders as $order) {
                // Check if the order is unpaid
                $paymentStatus = $order->paymentStatus;
                if(!in_array($paymentStatus, $paymentStatuses)) {
                    continue;
                }

                if(in_array($order->status, $statuses) && $order->invoicedue <= 0) {
                    continue;
                }

                $balance = $order->invoicedue - $order->paid_amount;

                if(in_array($order->status, $statuses) && $balance <= 0) {
                    continue;
                }

                if($order->hide_from_receivables) {
                    continue;
                }

                $rows[] = [
                    'id' => $order->id,
                    'date' => date('m/d/Y H:i', strtotime($order->ordereddate)),
                    'date_delivered' => $order->date_delivered ? date('m/d/Y H:i', strtotime($order->date_delivered)) : '--',
                    'invoice' => ($order->paymentcode . '-' . $order->id),
                    'borrower' => $order->borrower,
                    'group' => optional($order->groupData)->descrip,
                    'address' => $order->address,
                    'invoicedue' => $order->invoicedue,
                    'paid_amount' => $order->paid_amount,
                ];

                $totals['invoicedue'] += $order->invoicedue;
                $totals['paid_amount'] += $order->paid_amount;
            }
        }
        return [$rows, $totals];
    }

    /**
     * @return array
     */
    public function getCheckTypes()
    {
        return [
            self::CHECK => 'Check',
            self::CASHIER_CHECK => "Cashier's Check",
            self::MONEY_ORDER => 'Money Order',
            self::OTHER => 'Other',
        ];
    }

    /**
     * @param array $data
     * @return bool
     */
    public function applyBatchCheck($data = [])
    {
        $date = $data['date'];
        $check = $data['check_number'];
        $from = $data['from'];
        $type = $data['type'];
        $ids = $data['ids'];

        foreach($ids as $obj) {
            $amount = $obj['amount'];
            $orderId = $obj['id'];

            // Get order info
            $orderInfo = $this->orderRepository->getOrder($orderId);

            // Make sure amount is valid
            if($amount <= 0) {
                continue;
            }

            $orderInfo->paiddate = date('Y-m-d H:i:s');
            $orderInfo->payment_verified = 'Y';
            $orderInfo->billmelater = 'N';

            $checkReceived = new CheckReceived();
            $checkReceived->orderid = $orderId;
            $checkReceived->adminid = admin()->id;
            $checkReceived->checknum = $check;
            $checkReceived->checkamount = $amount;
            $checkReceived->check_from = $from;
            $checkReceived->dts = date('Y-m-d H:i:s');
            $checkReceived->date_recv = $date;
            $checkReceived->save();

            $logData = [
                'orderId' => $orderId,
                'info' => sprintf("Batch Check Payment Applied - %s<Br />Amount %s", $check, $amount),
            ];
            $this->ticketService->addNewLogEntry($logData);

            $update['paid_amount'] = ($orderInfo->paid_amount + $amount);

            // Change status to unassigned if the status was payment pending and the amount was paid in full
            // or allow partial payment is allowed
            if($orderInfo->status == self::PAYMENT_PENDING) {
                $group = $orderInfo->groupData;
                if(floatval($orderInfo->paid_amount + $amount) >= $orderInfo->invoicedue || optional($group)->allow_partial_payment == 'Y') {
                    $orderInfo->status = Order::STATUS_UNASSIGNED;
                }
            }
            $orderInfo->save();

            $cimCheckPayment = new CimCheckPayment();
            $cimCheckPayment->order_id = $orderId;
            $cimCheckPayment->created_date = time();
            $cimCheckPayment->user_id = admin()->id;
            $cimCheckPayment->amount = $amount;
            $cimCheckPayment->ref_type = $type;
            $cimCheckPayment->check_number = $check;
            $cimCheckPayment->date_received = strtotime($date);
            $cimCheckPayment->check_from = $from;
            $cimCheckPayment->save();
        }
        return true;
    }

    /**
     * @param array $data
     */
    public function applyBatchCC($data = [])
    {
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $address = $data['address'];
        $city = $data['city'];
        $state = $data['state'];
        $zip = $data['zip'];
        $card_number = $data['card_number'];
        $card_exp_month = $data['card_exp_month'];
        $card_exp_year = $data['card_exp_year'];
        $card_cvv = $data['card_cvv'];
        // Ids
        $ids = $data['ids'];
        $totalAmount = 0;

        // validate
        foreach($ids as $id) {
            $totalAmount += floatval($id['amount']);
        }

        if(!$totalAmount) {
            return [
                'error' => "Sorry, You have to select orders with amount to charge."
            ];
        }

        // Charge card
        $data = [
            'name' => trim($firstname . ' ' . $lastname),
            'first_name' => trim($firstname),
            'last_name' => trim($lastname),
            'number' => $card_number,
            'exp' => ($card_exp_month . $card_exp_year),
            'exp_month' => $card_exp_month,
            'exp_year' => $card_exp_year,
            'amount' => $totalAmount,
            'cvv' => $card_cvv,
            'zip' => $zip,
            'address' => $address,
            'city' => $city,
            'state' => $state,
        ];

        $result = $this->appraisalChargeCardMulti($data);
        $fdObject = $result['fd'];

        if(!$result['result']) {

            $errorMsg = sprintf("Could not charge the credit card. Error Returned: %s - %s. <Br />Bank Response: %s - %s",
                $fdObject->getErrorCode(), $fdObject->getErrorMessage(), $fdObject->getBankResponseCode(), $fdObject->getBankResponseMessage());
            return [
                'error' => $errorMsg
            ];
        }

        // It was charged add the records
        foreach($ids as $id) {
            $amount = $id['amount'];
            $orderId = $id['id'];

            $orderInfo = $this->orderRepository->getOrder($orderId);
            $order = $orderInfo;

            if(!$order) {
                continue;
            }

            // Set new amount for this order
            $info = $data;
            $info['amount'] = $amount;

            $this->appraisalAddProfileWithFD($orderId, $info, $fdObject);

            $update = ['paid_amount' => ($orderInfo->paid_amount+$amount)];

            $invoice = $orderInfo->invoicedue;
            if($orderInfo->is_cod == 'Y' && $orderInfo->paid_amount == 0) {
                $group = $orderInfo->groupData;
                if($group && $group->cod_payment_fee) {
                    $invoice = $invoice - $group->cod_payment_fee;
                    $update['invoicedue'] = $invoice;
                }
            }

            $logData = [
                'orderId' => $orderId,
                'info' => sprintf("$%s was charged on the credit card ending in %s. (Batch Payment)", $amount, StringHelper::maskCreditCard(StringHelper::formatCreditCard($data['number']))),
            ];
            $this->ticketService->addNewLogEntry($logData);
            // Add log entry

            // Change status to unassigned if the status was payment pending and the amount was paid in full
            // or allow partial payment is allowed
            if($orderInfo->status == self::PAYMENT_PENDING) {
                $group = $orderInfo->groupData;
                if(floatval($orderInfo->paid_amount+$amount) >= $orderInfo->invoicedue || $group->allow_partial_payment == 'Y') {
                    $update['status'] = Order::STATUS_UNASSIGNED;
                }
            }

            Order::save($order->id, $update);

            // Refresh Invoice
            $this->getApprOrderInvoiceDocument($orderId, true);
        }
    }

    /**
     * @param $orderId
     * @param array $data
     * @param $firstData
     * @return bool
     */
    protected function appraisalAddProfileWithFD($orderId, $data = [], $firstData)
    {
        $apprFdProfile = new ApprFdProfile();
        $apprFdProfile->order_id = $orderId;
        $apprFdProfile->card_name = $data['name'];
        $apprFdProfile->zipcode = $data['zip'] ?? 0;
        $apprFdProfile->created_date = time();
        $apprFdProfile->card_exp = $data['exp'] ?? 0;
        $apprFdProfile->user_id = admin()->id;
        $apprFdProfile->credit_type = StringHelper::creditCardCompany($data['number']);
        $apprFdProfile->credit_number = StringHelper::maskCreditCard(StringHelper::formatCreditCard($data['number']));
        $apprFdProfile->fd_profile_id = $firstData->getTransArmorToken();
        $apprFdProfile->cvv = $data['cvv'] ?? 0;
        $apprFdProfile->card_address = ucwords(strtolower($data['address']));
        $apprFdProfile->card_city = ucwords(strtolower($data['city']));
        $apprFdProfile->card_state = strtoupper($data['state']);
        $apprFdProfile->card_zip = $data['zip'];
        $apprFdProfile->save();

        $apprFdPayment = new ApprFDPayment();
        $apprFdPayment->order_id = $orderId;
        $apprFdPayment->created_date = time();
        $apprFdPayment->user_id = admin()->id;
        $apprFdPayment->fd_profile_id = $apprFdProfile->id;
        $apprFdPayment->fd_payment_profile_id = 0;
        $apprFdPayment->amount = $data['amount'];
        $apprFdPayment->return_code = intval($firstData->getErrorCode());
        $apprFdPayment->return_message = $firstData->getErrorMessage();
        $apprFdPayment->bank_code = intval($firstData->getBankResponseCode());
        $apprFdPayment->bank_response = $firstData->getBankResponseMessage();
        $apprFdPayment->ref_type = ApprFDPayment::CHARGE;
        $apprFdPayment->is_success = 1;
        $apprFdPayment->auth_code = $firstData->getAuthNumber() ?? '';
        $apprFdPayment->trans_id = '';
        $apprFdPayment->avs_code = $firstData->getAvsResponseCode();
        $apprFdPayment->avs_message = $firstData->getAvsResponseMessage();
        $apprFdPayment->cvv_code = $firstData->getCvvResponseCode();
        $apprFdPayment->cvv_message = $firstData->getCvvResponseMessage();
        $apprFdPayment->save();

        return true;
    }
}