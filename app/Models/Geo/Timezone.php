<?php

namespace App\Models\Geo;

use App\Models\BaseModel;
use App\Models\Geo\Region;
use App\Models\Geo\State;

class Timezone extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'timezones';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name'];


    /**
     * get state timezone
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timezoneStates()
    {
        return $this->hasMany(State::class);
    }
}
