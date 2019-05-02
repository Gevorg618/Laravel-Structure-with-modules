<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;
use App\Models\Management\WholesaleLenders\UserGroupLender;

class UserGroupLenderUserManager extends BaseModel
{
    protected $table = 'user_group_lender_user_manager';

    protected $fillable = [
        'lenderid',
        'userid'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lender()
    {
        return $this->belongsTo(UserGroupLender::class, 'lenderid');
    }

    public function scopeJoinLenders($query)
    {
      $query->leftJoin('user_group_lender', 'user_group_lender.id', '=', 'user_group_lender_user_manager.lenderid');
    }
}
