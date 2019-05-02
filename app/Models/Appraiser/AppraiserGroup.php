<?php

namespace App\Models\Appraiser;

use App\Models\BaseModel;
use App\Models\Users\User;
use App\Models\Traits\ModifiedUser;
use Carbon\Carbon;

class AppraiserGroup extends BaseModel
{
    use ModifiedUser;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'appr_group';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'created_by',
        'managerid'
    ];

    /**
     * appraiser groups has many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'appr_group_user', 'groupid', 'userid');
    }

    /**
     * appraiser group owns by manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'managerid')->with('userData');
    }

    /**
     * group is created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userData');
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    /**
     * group has many appraisers
     *
     * @return mixed
     */
    public function appraisers()
    {
        return $this->belongsToMany(User::class, 'appr_group_user', 'groupid', 'userid')->with('userData');
    }
}
