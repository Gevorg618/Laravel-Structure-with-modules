<?php

namespace Modules\Admin\Repositories\Geo;

use App\Models\Geo\State;

class StatesRepository
{
    /**
     * Object of State class
     *
     * @var $user
     */
    private $state;

    /**
     * StatesRepository constructor.
     */
    public function __construct()
    {
        $this->state = new State();
    }

    /**
     * get all states
     *
     * @return collection
     */
    public function getStates()
    {
        return $this->state->select('abbr', 'state')->get();
    }

    /**
     * get multiple states by abbreviation
     *
     * @param $abbr
     * @return mixed
     */
    public function getMultipleStatesByAbbr($abbr)
    {
        return $this->state->whereIn('abbr', $abbr)->get();
    }

    /**
     * get state by abbreviation
     *
     * @param $abbr
     * @return mixed
     */
    public function getStateByAbbr($abbr)
    {
        return $this->state->where('abbr', $abbr)->first();
    }

    /**
     * get all states count
     *
     * @return collection
     */
    public function getStatesCount()
    {
        return $this->state->count();
    }

    
}