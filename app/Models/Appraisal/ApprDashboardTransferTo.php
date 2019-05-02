<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

/**
 * Class ApprDashboardTransferTo
 * @package App\Models\Appraisal
 */
class ApprDashboardTransferTo extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'appr_dashboard_transfer_to';

    /**
     * collection
     */
    public function apprOrders()
    {
    	return $this->belongsTo('App\Models\Appraisal\Order', 'orderid');
    }

    /**
     * collection
     */
    public function fromUser()
    {
    	return $this->belongsTo('App\Models\Users\User', 'fromuser');
    }

    /**
     * collection
     */
    public function toUser()
    {
    	return $this->belongsTo('App\Models\Users\User', 'touser');
    }
}
