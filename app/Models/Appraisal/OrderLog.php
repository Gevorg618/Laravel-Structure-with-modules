<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;
use Carbon\Carbon;

class OrderLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_log';

    public $timestamps = false;

     protected $fillable = [
        'orderid',
        'userid',
        'ticketid',
        'email',
        'info',
        'html_content',
        'type_id',
        'is_client_visible',
        'is_appr_visible',
        'is_highlight'
    ];

    public static function getLastLogEntryDate($orderId)
    {
        $log = self::select('dts')->where('orderid', $orderId)->orderBy('dts', 'DESC')->limit(1)->get();
        if ($log->isNotEmpty()) {
            return Carbon::parse($log[0]->dts)->format('m/d/Y H:i');
        }
        return '--';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderid');
    }
}
