<?php

namespace App\Models\Calendar;

use App\Models\BaseModel;

class CalendarEventUser extends BaseModel
{

    protected $table = 'calendar_event_user';

    protected $fillable = ['event_id', 'user_id'];

    public $timestamps = false;
}
