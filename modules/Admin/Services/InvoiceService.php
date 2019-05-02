<?php

namespace Modules\Admin\Services;


use App\Models\Appraisal\Order;
use App\Models\Management\ZipCode;

class InvoiceService
{
    /**
     * @param Order $order
     * @return array
     */
    public static function getHistoryData(Order $order)
    {
        $chargeAmount = $order->amountdue;
        if($order->sales_tax > 0) {
            if($order->orig_price > 0) {
                $chargeAmount = $order->orig_price;
            } else {
                $chargeAmount = $order->amountdue - ($order->amountdue * $order->sales_tax / 100);
            }
        }

        $user = $order->userData;
        $group = $order->groupData;

        if($group->sub_deliveryfee_frominvoice == "Y" AND $group->mail_appr_addfee != 0 AND $order->final_appraisal_borrower_sendtopostalmail == "Y") {
            $order->amountdue -= ($group->mail_appr_addfee * $order->final_appraisal_postal_count);
        }

        $addendas = $order->getAddendas();

        $history = $order->getPaymentHistory();
        $checks = $order->getCheckPaymentHistory();
        $adjustments = $order->getAdjustmentsPaymentHistory();
        return [
            $chargeAmount,
            $user,
            $group,
            $addendas,
            $history,
            $checks,
            $adjustments
        ];
    }

    /**
     * @param $order
     * @param $chargeAmount
     * @return array
     */
    public static function getTaxData($order, $chargeAmount)
    {
        $postal = ZipCode::where('zip_code', $order->propzip)->first();
        $states = getStates();

        $salesMsg = str_replace('{x}', '%', sprintf("%s{x} Sales Tax - %s - %s", $order->sales_tax, $states[$postal->state], ucwords(strtolower($postal->county))));
        if($order->orig_price > 0) {
            $taxPrice = $chargeAmount * $order->sales_tax / 100;
        } else {
            $taxPrice = $order->amountdue * $order->sales_tax / 100;
        }
        return [
            $postal,
            $states,
            $salesMsg,
            $taxPrice,
        ];
    }

    /**
     * @param $item
     * @return array
     */
    public static function getPaymentHistoryData($item)
    {
        $amount = "$".number_format($item->amount,2);
        $info = sprintf("Credit Card Payment - %s - From: %s", str_replace('XXXX-XXXX-', '', $item->credit_number), $item->card_name);
        if($item->ref_type == 'REFUND') {
            $info = "Credit Card Refund - ".str_replace('XXXX-XXXX-', '', $item->credit_number);
            $amount = '('."$".number_format($item->amount,2).')';
        }
        return [
            $amount,
            $info,
        ];
    }

    /**
     * @param $item
     * @return array
     */
    public static function getCheckHistoryData($item)
    {
        $amount = "$".number_format($item->amount,2);
        $info = sprintf("Check Payment - %s - Received Date: %s - From: %s", $item->check_number, date('m/d/Y', $item->date_received), $item->check_from);
        if($item->ref_type == 'REFUND') {
            $info = "Check Refund - ".$item->check_number;
            $amount = '('."$".number_format($item->amount,2).')';
        }
        return [
            $amount,
            $info,
        ];
    }

    /**
     * @param $item
     * @return array
     */
    public static function getAdjustmentsData($item)
    {
        $amount = "$".number_format($item->amount,2);
        $info = sprintf("Adjustment - %s", getAdminReasonTitleByKey($item->reason));
        return [
            $amount,
            $info,
        ];
    }

    /**
     * @param $order
     * @return string
     */
    public static function getAmountDue($order)
    {
        if($order->paid_amount < 0) {
            $amountDue = $order->paid_amount;
        } else {
            $amountDue = number_format($order->invoicedue-$order->paid_amount, 2);
        }
        return number_format($amountDue, 2);
    }
}