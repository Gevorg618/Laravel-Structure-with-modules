<?php

namespace Modules\Admin\Services\Ticket;

class MercuryService
{
    protected $createTicket;

    const COMMENT_ACTION_REQUIRED = 900030;

    /**
     * MercuryService constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param \App\Models\Appraisal\Order $order
     * @param string $subject
     * @param string $comment
     * @return bool|array
     */
    public function sendMessage($order, $subject, $comment)
    {
        $relation = $order->mercury;

        if (!$relation) {
            return false;
        }

        $xml = view('integrations.mercury.message', [
            'relationId' => $relation->mercury_oid,
            'statusId' => 900030,
            'comment' => sprintf("%s\n%s", $subject, $comment),
            'extra' => null,
        ])->render();

        // Send the update
        $endPoint = config('services.mercury.in_qa')
            ? config('services.mercury.status_qa')
            : config('services.mercury.status_live');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $endPoint, [
                'form_params' => [
                    'UserName' => config('services.mercury.username'),
                    'Password' => config('services.mercury.password'),
                    'XMLPost' => $xml
                ]
            ]);

            $xmlSource = (string)$response->getBody();
            libxml_use_internal_errors(true);
            $data = simplexml_load_string(
                $xmlSource, 'SimpleXMLElement', LIBXML_PARSEHUGE | LIBXML_NOCDATA
            );

            if (!$data) {
                return $this->prepareTicketData($order, 'XML NO DATA');
            }

            // If this is an error then log
            if ($data->bResult == 'false') {
                return $this->prepareTicketData($order, 'XML Returned Error', $data->szError);
            }

            return true;

        } catch (\Exception $e) {
            $errorMessage = sprintf('Error: %s. OID: %s', $e->getMessage(), $order->id);
            return $this->prepareTicketData($order, 'Status Update Error', $errorMessage);
        }
    }

    /**
     * @param \App\Models\Appraisal\Order $order
     * @param string $subject
     * @param string $message
     * @return array
     */
    protected function prepareTicketData($order, $subject, $message = '')
    {
        $body = 'A recent communication to the Mercury Network failed due to a temporary system error.';
        $body .= ' Please attempt to resend the notification. <br>';
        $body .= $message;

        return [
            'orderId' => $order->id,
            'teamId' => $order->teamId,
            'to' => config('app.help_email'),
            'from' => config('app.help_email'),
            'subject' => sprintf('%s Mercury Network Error: %s', $order->id, $subject),
            'body' => $body,
            'addLog' => true,
        ];
    }
}