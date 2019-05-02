<?php

namespace Modules\Admin\Services\Ticket;

class FNCService
{
    protected $order;

    /**
     * FNCService constructor.
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
     * @return bool
     */
    public function sendMessage($order, $subject, $message)
    {
        $relation = $order->fnc;

        if (!$relation) {
            return false;
        }

        try {
            $xml = view('integrations.fnc.message', [
                'subject' => $subject,
                'message' => $message,
                'folderId' => $relation->folder_id,
                'portId' => $relation->port_id,
            ])->render();

            $endPoint = config('services.fnc.in_qa')
                ? config('services.fnc.qa')
                : config('services.fnc.live');

            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $endPoint . 'add_message.ashx', [
                'form_params' => ['XMLData' => $xml]
            ]);

            $this->order->addOrderLog($order->id, 'FNC: Public Log Sent');
            return $response;

        } catch (\Exception $e) {
            $errorMessage = sprintf('Error: %s. OID: %s', $e->getMessage(), $order->id);

            $this->order->addOrderLog($order->id, 'FNC: Failed sending log entry.<br>' . $errorMessage);
            return $errorMessage;
        }
    }
}