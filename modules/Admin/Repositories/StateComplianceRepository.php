<?php

namespace Modules\Admin\Repositories;


use App\Models\Users\StateCompliance;

class StateComplianceRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getStateComplianceTakenStates()
    {
        return StateCompliance::orderBy('state')->pluck('state', 'state');
    }

    /**
     * @param $state
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getStateComplianceRecordByState($state)
    {
        return StateCompliance::where('state', $state)->first();
    }
}
