<?php

namespace App\Models\Management\AdminGroup;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BaseModel;

class AdminGroup extends BaseModel
{
    use SoftDeletes;

    protected $table = 'admin_group';

    protected $fillable = [
        'title',
        'is_protected',
        'color',
        'style',
    ];

    protected $dates = ['deleted_at'];

    public function allGroups()
    {
        return $this->orderBy('title', 'ASC')->get();
    }
}
