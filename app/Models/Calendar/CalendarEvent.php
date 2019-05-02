<?php

namespace App\Models\Calendar;

use App\Models\BaseModel;

class CalendarEvent extends BaseModel
{
    protected $table = 'calendar_event';

    protected $fillable = [
        'title',
        'description',
        'is_private',
        'all_day',
        'start_date',
        'end_date',
        'created_date',
        'created_by',
        'calendar',
    ];

    public $timestamps = false;
}
