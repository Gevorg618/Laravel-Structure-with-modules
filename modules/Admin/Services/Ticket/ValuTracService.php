<?php

namespace Modules\Admin\Services\Ticket;

class ValuTracService
{
    protected $order;

    /**
     * ValuTracService constructor.
     * @param OrderService $order
     */
    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }

    /**
     * @param \App\Models\Appraisal\Order $order
     * @param string $subject
     * @param string $message
     * @return bool|string
     */
    public function sendMessage($order, $subject, $message)
    {
        $relation = $order->valuTrac;
        if (!$relation) {
            return false;
        }

        try {
            $params = [
                'orderTrackingId' => $relation->valutrac_oid,
                'email' => '',
                'name' => admin()->fullname,
                'note' => sprintf("%s\n%s", $subject, $message),
                'apiKey' => config('services.valutrac.in_qa')
                    ? config('services.valutrac.test_api_key')
                    : config('services.valutrac.production_api_key')
            ];

            $endPoint = config('services.valutrac.in_qa')
                ? config('services.valutrac.qa')
                : config('services.valutrac.live');

            $options = [
                'exceptions' => true,
                'trace' => 1,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'location' => $endPoint,
                'encoding' => 'utf-8',
            ];

            $client = new \SoapClient($endPoint . '?wsdl', $options);
            $result = $client->AddNote($params);

            if ($result->AddNoteResult == 'OK') {
                $message = 'Public Log Sent';
            } else {
                $message = $result->AddNoteResult;
            }

            $this->order->addOrderLog($order->id, 'ValuTrac: ' . $message);
            return $message;

        } catch (\Exception $e) {
            $this->order->addOrderLog($order->id, 'ValuTrac: Failed sending log entry.<br>' . $e->getMessage());
            return $e->getMessage();
        }
    }
}