<?php

namespace App\Models\DocuVault;

use App\Models\BaseModel;
use App\Models\Appraisal\Order;

class Notification extends BaseModel
{
    protected $table = 'document_vault_notification';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprasialOrders()
    {
        return $this->hasMany(Order::class, 'id', 'order_id')->with(['notificationGroupData', 'lenderRecord']);
    }
}
