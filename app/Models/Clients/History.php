<?php

namespace App\Models\Clients;

use App\Models\BaseModel;

class History extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_group_history_logs';

    protected $fillable = ['group_id', 'created_date', 'created_by', 'note'];

    public $timestamps = false;
}
