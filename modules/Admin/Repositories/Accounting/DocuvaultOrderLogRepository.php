<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\DocuVault\OrderLog;

class DocuvaultOrderLogRepository
{
    public function insert($orderId, $typeId, $visible, $subject)
    {
        $model = new OrderLog();
        $model->order_id = $orderId;
        $model->type_id = $typeId;
        $model->client_visible = $visible ? 1 : 0;
        $model->message = $subject;
        $model->userid = admin()->id;
        $model->dts = date('Y-m-d H:i:s');
        return $model->save();
    }
}