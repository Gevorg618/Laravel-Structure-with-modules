<?php

namespace App\Models\AlternativeValuation;

use App\Models\BaseModel;

/**
 * Class OrderStatus
 * @package App\Models\Appraisal
 */
class OrderStatus extends BaseModel
{
    const COMPLETE = 8;
    const CANCELLED_TRIP_FEE = 20;
    /**
     * @var string
     */
    protected $table = 'alt_order_status';
}
