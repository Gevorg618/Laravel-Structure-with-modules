<?php

namespace Modules\Admin\Repositories\Customizations;

use App\Models\Customizations\TurnTimeByState;

class TurnTimeByStateRepository
{
    private $state;

    /**
     * TurnTimeByStateRepository constructor.
     */
    public function __construct()
    {
        $this->state = new TurnTimeByState();
    }

    /**
     * getTurnTimeByState
     * @param $full
     * @return Array
     */
    public function getTurnTimeByState($full = false)
    {
        $data = [];
        $states = $this->state->orderBy('state', 'ASC')->get();
        foreach ($states as $state) {
            if ($full) {
                $data[$state->state] = sprintf("%s - %s Days Turn Time, Expected Due Date: %s", $state->state, $state->days, date('m/d/Y', strtotime(sprintf('+%s days', $state->days))));
            } else {
                $data[$state->state] = sprintf("%s - %s Days", $state->state, $state->days);
            }
        }

        return $data;
    }

    /**
     * getTurnTimeByState
     * @return Collection
     */
    public function getTurnTimeByStatesList()
    {
        return $this->state->orderBy('state', 'ASC')->get();
    }

    /**
     * update or create
     * @param $inputs
     * @return void
     */
    public function save($inputs)
    {
        $this->state->where('state', $inputs['state'])->delete();
        $this->state->create([
            'state' => $inputs['state'],
            'days' => $inputs['days']
        ]);
    }
}
