<?php

namespace App\Models\Ticket;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Management\UserType;
use App\Models\Customizations\Status;

class Category extends BaseModel
{
    protected $table = 'tickets_category';

    protected $fillable = [
        'name',
        'skey',
        'bgcolor',
        'textcolor',
    ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->userTypes()->detach();
            $category->orderStatuses()->detach();
        });
    }

    /**
     * User types
     * @return mixed
     */
    public function userTypes()
    {
        return $this->belongsToMany(
            UserType::class,
            'tickets_category_visible',
            'category_id',
            'user_type'
        );
    }

    /**
     * Order Statuses
     * @return mixed
     */
    public function orderStatuses()
    {
        return $this->belongsToMany(
            Status::class,
            'tickets_category_appraisal_status_rel',
            'category_id',
            'status_id'
        );
    }

    /**
     * Set User Type
     * @param $user_type_id
     * @return void
     */
    public function setUserType($user_type_id)
    {
        if ($user_type_id) {
            if (UserType::where('id', $user_type_id)->exists())
                $this->userTypes()->attach([$user_type_id]);
        }
    }

    /**
     * Set Statuses
     * @param $order_status_id
     * @return void
     */
    public function setStatus($order_status_id)
    {
        if ($order_status_id) {
            if (Status::where('id', $order_status_id)->exists())
                $this->orderStatuses()->attach([$order_status_id]);
        }
    }

    /**
     * Update User Type
     * @param $user_type_id
     * @return void
     */
    public function updateUserType($user_type_id)
    {
        if ($user_type_id) {
            if (UserType::where('id', $user_type_id)->exists()) {
                $this->userTypes()->detach();
                $this->userTypes()->attach([$user_type_id]);
            }
        }
    }

    /**
     * Update Statuses
     * @param $order_status_id
     * @return void
     */
    public function updateStatus($order_status_id)
    {
        if ($order_status_id) {
            if (Status::where('id', $order_status_id)->exists()) {
                $this->orderStatuses()->detach();
                $this->orderStatuses()->attach([$order_status_id]);
            }
        }
    }

    /**
     * Check do we have new category
     */
    public function beforeSave()
    {
        if (!$this->exists) {
            $this->attributes['skey'] = getCode($this->attributes['name']);
        }
        return parent::beforeSave();
    }
}