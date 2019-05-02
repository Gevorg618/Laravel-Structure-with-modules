<?php

namespace Modules\Admin\Services\Accounting\Batch;


use App\Models\Accounting\CimCheckPayment;
use App\Models\DocuVault\CimCheckPayment as DocuvaultCimCheckPayment;
use App\Models\Appraisal\OrderFile;
use App\Models\Accounting\CheckReceived;
use App\Models\Accounting\DocuvaultCheckReceived;
use App\Models\Tools\Setting;
use Illuminate\Support\Collection;
use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Repositories\Accounting\AltOrderLogTypeRepository;
use Modules\Admin\Repositories\Accounting\ApprDocuvaultOrderRepository;
use Modules\Admin\Repositories\Accounting\DocuvaultOrderLogRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;
use Modules\Admin\Services\Ticket\TicketModerationService;

/**
 * Class BatchDocuvaultService
 * @package Modules\Admin\Services
 */
class BatchDocuvaultService extends BatchService
{
    const DOCUVAULT = 'docuvault';

    const APPR = 'appr';

    const CHECK = "Check";

    const CASHIER_CHECK = "Cashier's Check";

    const MONEY_ORDER = 'Money Order';

    const OTHER = 'Other';

    const TYPE_PROCESS = 'PROCESS';

    protected $orderRepo;

    protected $docuvaultOrderRepo;

    protected $docuvaultOrderLogRepo;

    protected $logTypeRepository;

    protected $ticketModerationService;

    /**
     * BatchDocuvaultService constructor.
     * @param OrderRepository $orderRepo
     * @param ApprDocuvaultOrderRepository $docuvaultOrderRepo
     * @param DocuvaultOrderLogRepository $docuvaultOrderLogRepo
     * @param AltOrderLogTypeRepository $logTypeRepository
     * @param TicketModerationService $moderationService
     */
    public function __construct(
        OrderRepository $orderRepo,
        ApprDocuvaultOrderRepository $docuvaultOrderRepo,
        DocuvaultOrderLogRepository $docuvaultOrderLogRepo,
        AltOrderLogTypeRepository $logTypeRepository,
        TicketModerationService $moderationService
    )
    {
        $this->orderRepo = $orderRepo;
        $this->docuvaultOrderRepo = $docuvaultOrderRepo;
        $this->docuvaultOrderLogRepo = $docuvaultOrderLogRepo;
        $this->logTypeRepository = $logTypeRepository;
        $this->ticketModerationService = $moderationService;
    }

    /**
     * @return array
     */
    public function getOrderTypes()
    {
        return [
            self::APPR => 'Appraisals',
            self::DOCUVAULT => 'DocuVault',
        ];
    }

    /**
     * @param $type
     * @param $dateFrom
     * @param $dateTo
     * @param $clients
     * @return \Illuminate\Support\Collection
     */
    public function getOrdersForBatch($type, $dateFrom, $dateTo, $clients)
    {
        if ($type == self::DOCUVAULT) {
            return $this->docuvaultOrderRepo->getOrdersForBatch($dateFrom, $dateTo, $clients);
        }
        return $this->orderRepo->getOrdersForBatch($dateFrom, $dateTo, $clients);
    }

    /**
     * @return array
     */
    public function getCheckTypes()
    {
        return [
            'CHECK' => self::CHECK,
            'CASHIER_CHECK' => self::CASHIER_CHECK,
            'MONEY_ORDER' => self::MONEY_ORDER,
            'OTHER' => self::OTHER,
        ];
    }

    /**
     * @param Collection $orders
     * @param $orderType
     * @return array
     */
    public function setRowsAndTotals(Collection $orders, $orderType)
    {
        $rows = [];
        $ids = [];
        $totals = [
            'invoicedue' => 0,
            'paid_amount' => 0
        ];
        if ($orders && count($orders)) {
            foreach ($orders as $order) {
                $amountOwed = 0;
                $isEmail = false;

                if ($orderType == self::DOCUVAULT) {
                    // Check if this is a mail or email
                    if ($order->final_appraisal_borrower_sendtopostalmail == 'Y') {
                        $amountOwed = $order->final_appraisal_borrower_sendtopostalmail_amount;
                    } elseif ($order->final_appraisal_borrower_sendtoemail == 'Y') {
                        $amountOwed = $order->invoicedue;
                        $isEmail = true;
                    }

                    // Paid amount
                    $paidAmount = $order->paid_amount;
                    $balance = $amountOwed - $paidAmount;

                } else {
                    if ($order->final_appraisal_borrower_sendtopostalmail == 'Y') {
                        $amountOwed = $order->final_appraisal_borrower_sendtopostalmail_amount;
                    } elseif ($order->final_appraisal_borrower_sendtoemail == 'Y') {
                        $amountOwed = 0;
                        $isEmail = true;
                    }

                    // Paid amount
                    $paidAmount = $order->mail_paid_amount;
                    $balance = $amountOwed - $paidAmount;
                }

                if ($isEmail) {
                    if ($amountOwed <= 0) {
                        continue;
                    }
                } else {
                    // Check balance
                    if ($amountOwed > 0 && $balance <= 0) {
                        continue;
                    }
                }

                $ids[$order->id] = $order->id;

                $rows[$order->id] = [
                    'id' => $order->id,
                    'date' => date('m/d/Y H:i', strtotime($order->ordereddate)),
                    'notification_sent' => optional($order->notification)->created_date ? date('m/d/Y H:i', $order->notification_sent) : '--',
                    'invoice' => $order->id,
                    'borrower' => $order->borrower,
                    'group' => $order->groupData->descrip,
                    'address' => $order->address,
                    'invoicedue' => $amountOwed,
                    'paid_amount' => $paidAmount,
                ];

                $totals['invoicedue'] += $amountOwed;
                $totals['paid_amount'] += $paidAmount;
            }
        }
        return [
            $rows,
            $totals,
            $ids
        ];
    }

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getOrderByIdAndType($id, $type)
    {
        if ($type == self::DOCUVAULT) {
            return $this->docuvaultOrderRepo->getOrderById($id);
        }
        return $this->orderRepo->getOrderById($id);
    }

    /**
     * @param $orderId
     * @param $subject
     * @param bool $visible
     * @return bool
     */
    public function docuvaultAddQuickLogEntry($orderId, $subject, $visible = false)
    {
        $typeId = $this->logTypeRepository->getIdByType(self::TYPE_PROCESS);
        return $this->docuvaultOrderLogRepo->insert($orderId, $typeId, $visible, $subject);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function applyBatchCheck($data = [])
    {
        list($ids, $orderType, $check, $from, $date, $type) = $data;
        foreach ($ids as $obj) {
            $amount = $obj['amount'];
            $orderId = $obj['id'];

            // Get order info
            $orderInfo = $this->getOrderByIdAndType($orderId, $orderType);

            // Make sure amount is valid
            if ($amount <= 0) {
                continue;
            }

            // Update
            $checkInfo = [
                'orderid' => $orderId,
                'adminid' => admin()->id,
                'checknum' => $check,
                'checkamount' => $amount,
                'check_from' => $from,
                'dts' => date('Y-m-d H:i:s'),
                'date_recv' => $date,
            ];

            $info = [
                'order_id' => $orderId,
                'created_date' => time(),
                'user_id' => admin()->id,
                'amount' => $amount,
                'ref_type' => $type,
                'check_number' => $check,
                'date_received' => strtotime($date),
                'check_from' => $from,
                'is_visible' => 0,
            ];

            if ($orderType == self::DOCUVAULT) {
                $orderInfo->paid_amount += $amount;
                $orderInfo->save();

                $this->docuvaultAddQuickLogEntry($orderId, sprintf("Mail Fee: Received Check %s<Br />Amount $%s", $check, $amount));

                DocuvaultCimCheckPayment::create($info);
                DocuvaultCheckReceived::create($checkInfo);
            } else {
                $orderInfo->mail_paid_amount += $amount;
                $orderInfo->save();

                $this->ticketModerationService->addNewLogEntry([
                    'orderId' => $orderId,
                    'info' => sprintf("Mail Fee: Received Check %s<Br />Amount $%s", $check, $amount),
                ]);

                CimCheckPayment::create($info);
                CheckReceived::create($checkInfo);
            }
        }
        return true;
    }


    public function applyCreditCard($data = [])
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
        $orderType = $data['ordertype'];
        // Ids
        $ids = $data['ids'];
        $totalAmount = 0;

        // validate
        foreach ($ids as $id) {
            $totalAmount += floatval($id['amount']);
        }

        if (!$totalAmount) {
            return [
                'error' => 'Sorry, You have to select orders with amount to charge.'
            ];
        }

        // Charge card
        $data = [
            'first_name' => $firstname,
            'last_name' => $lastname,
            'name' => trim($firstname . ' ' . $lastname),
            'number' => $card_number,
            'exp_month' => $card_exp_month,
            'exp_year' => $card_exp_year,
            'exp' => ($card_exp_month . $card_exp_year),
            'amount' => $totalAmount,
            'cvv' => $card_cvv,
            'zip' => $zip,
            'address' => $address,
            'city' => $city,
            'state' => $state,
        ];

        $result = $this->appraisalChargeCardMulti($data);
        $fdObject = $result['fd'];

        if (!$result['result']) {

            $errorMsg = sprintf("Could not charge the credit card. Error Returned: %s - %s. <Br />Bank Response: %s - %s",
                $fdObject->getErrorCode(), $fdObject->getErrorMessage(), $fdObject->getBankResponseCode(), $fdObject->getBankResponseMessage());
            return [
                'error' => $errorMsg
            ];
        }

        // It was charged add the records
        foreach ($ids as $id) {
            $amount = $id['amount'];
            $orderId = $id['id'];

            $order = $this->getOrderByIdAndType($orderId, $orderType);
            // Get order info


            if (!$order) {
                continue;
            }

            // Set new amount for this order
            $info = $data;
            $info['amount'] = $amount;

            if ($orderType == self::DOCUVAULT) {
                $order->paid_amount += $amount;
                $order->save();
                $this->docuvaultAddQuickLogEntry($orderId, sprintf("Mail Fee: $%s was charged on the credit card ending in %s. (Batch Payment)", $amount,
                    StringHelper::maskCreditCard(StringHelper::formatCreditCard($data['number']))));
            } else {
                $order->mail_paid_amount += $amount;
                $order->save();
                // Add log entry

                $this->ticketModerationService->addNewLogEntry([
                    'orderId' => $orderId,
                    'info' => sprintf("Mail Fee: $%s was charged on the credit card ending in %s. (Batch Payment)",
                        $amount, StringHelper::maskCreditCard(StringHelper::formatCreditCard($data['number']))),
                ]);
            }

            // Refresh Invoice
            $this->getApprOrderInvoiceDocument($orderId, true);
        }
        return ['success' => true];
    }
}