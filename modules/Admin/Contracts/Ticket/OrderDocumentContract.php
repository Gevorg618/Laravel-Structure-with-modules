<?php

namespace Modules\Admin\Contracts\Ticket;

interface OrderDocumentContract
{
    /**
     * @param $order
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrderDocumentsByLocationCode($order, $code);

    /**
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDocumentsByLocationCode($code);
}