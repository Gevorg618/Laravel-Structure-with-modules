<?php

namespace App\Models\Api;

use App\Models\BaseModel;
use Carbon\Carbon;

class Subscriber extends BaseModel
{
    protected $table = 'api_subscriber';

    protected $fillable = [
        'subscriber_hash_id',
        'api_id',
        'subscribe_url',
        'subscribe_failed_attempts',
        'subscribe_last_used_date',
    ];

    public $timestamps = false;

    public static function getAPISubscribers($id)
    {
        $data = [];
        $subscribers = self::where('api_id', $id)->get();
        if($subscribers) {
            foreach($subscribers as $row) {
                $data[$row->subscriber_hash_id] = [
                    'id' => $row->subscriber_hash_id,
                    'url' => $row->subscribe_url,
                    'active' => $row->subscribe_active,
                    'failed_attempts' => $row->subscribe_failed_attempts,
                    'last_used' => $row->subscribe_last_used_date ? Carbon::createFromTimeStamp($row->subscribe_last_used_date)->format('m/d/Y H:i:s') : '',
                ];
                $types = $row->subscriberType;
                foreach($types as $type) {
                    $data[$row->subscriber_hash_id]['types'][] = $type->type;
                }
                $fields = $row->subscriberField;
                foreach($fields as $field) {
                    $data[$row->subscriber_hash_id]['fields'][] = $field->field;
                }
            }
        }
        return $data;
    }

    public function subscriberType()
    {
        return $this->hasMany('App\Models\Api\SubscriberType', 'subscriber_id');
    }

    public function subscriberField()
    {
        return $this->hasMany('App\Models\Api\SubscriberField', 'subscriber_id');

    }
}
