<?php

use Illuminate\Support\Facades\DB;
use App\Models\Appraisal\Order;
use App\Models\Users\User;
use App\Models\Customizations\Status;
use App\Models\Clients\Client;
use App\Services\OrderFunctionsService;

function convertOrderKeysToValues($message, $order)
{
    if (!is_null($order)) {
        $order->ucdpSubmissions = getLatestSubmission($order->id, 'ucdp');
        return $order->convertKeys($message);
    }

    return $message;
}

function getLatestSubmission($id, $type)
{
    return OrderFunctionsService::hasThirdPartySubmission($id, $type);
}
