<?php

namespace Modules\Admin\Services\Accounting\Batch;


use App\Models\Appraisal\OrderFile;
use App\Models\Tools\Setting;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;

abstract class BatchService
{
    /**
     * @param array $data
     * @return array
     */
    public function appraisalChargeCardMulti($data = [])
    {
        // Init
        $returnTest = false;
        if ($data['number'] == '4111111111111111') {
            $returnTest = true;
        }
        $firstData = $this->getFirstDataObject($returnTest);
        $card = new CreditCard([
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'number' => $data['number'],
            'expiryMonth' => $data['exp_month'],
            'expiryYear' => $data['exp_year'],
            'cvv' => $data['cvv'],
        ]);

        $transaction = $firstData->purchase([
            'description' => 'Your order for widgets',
            'amount' => $data['amount'],
            'card' => $card,
        ]);
        $response = $transaction->send();
        return $response->isSuccessful() ? [
            'result' => true,
            'info' => $response->getData(),
        ] : [
            'result' => false,
            'info' => $response->getData(),
        ];
    }

    /**
     * @param bool $test
     * @return \Omnipay\Common\GatewayInterface
     */
    protected function getFirstDataObject($test = false)
    {
        $gateway = Omnipay::create('FirstData_Payeezy');
        if (Setting::getSetting('first_data_test_enable') || $test) {
            $gateway->initialize([
                'gatewayId' => Setting::getSetting('first_data_test_api_login'),
                'password' => Setting::getSetting('first_data_test_api_trans_key'),
                'testMode' => true,
            ]);
        } else {
            $gateway->initialize([
                'gatewayId' => Setting::getSetting('first_data_api_login'),
                'password' => Setting::getSetting('first_data_api_trans_key'),
                'testMode' => false,
            ]);
        }
        return $gateway;
    }

    /**
     * @param $orderId
     * @param bool $refresh
     * @return bool|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function getApprOrderInvoiceDocument($orderId, $refresh = false)
    {
        // Check if we have that enabled
        if (!Setting::getSetting('invoice_enable')) {
            return false;
        }

        // See if we have one
        $row = OrderFile::where('order_id', $orderId)
            ->where('is_invoice', 1)->first();

        if (!$row || $refresh) {
            // Create
            \Artisan::call('invoice:generate', [
                'id' => $orderId,
                'admin' => admin()->id
            ]);
        }

        // Return document
        return $row;
    }
}