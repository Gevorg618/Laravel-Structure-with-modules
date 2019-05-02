<?php

namespace App\Models\Geo;

use App\Models\BaseModel;
use App\Models\Geo\Timezone;
use App\Models\Geo\Region;

class State extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'abbr',
        'state',
        'timezone_id',
        'region_id'
    ];

    /**
     * get state timezone
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stateTimezone()
    {
        return $this->belongsTo(Timezone::class, 'timezone_id');
    }

    /**
     * get state region
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stateRegion()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * get state Adjacent states
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stateAdjacent()
    {
        return $this->belongsToMany(State::class, 'state_adjacent', 'state_1', 'state_2');
    }
}
