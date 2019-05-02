<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\State;
use App\Models\Geo\Timezone;
use App\Models\Geo\Region;

class UpdateStatesTimezoneRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = State::get()->pluck('abbr', 'id');

        foreach ($states as $stateId => $abbr) {
        	
        	$timezoneName = getTimeZoneByState($abbr);
        	
        	$timeZone = Timezone::where('name', $timezoneName)->first();

        	$regionName = getRegionsByStateAbbr($abbr);

        	$region = Region::where('name', $regionName)->first();

        	if ($timeZone && $region) {
        		$data = [
        			'timezone_id' => $timeZone->id,
        			'region_id' => $region->id
        		];

        		State::where('id', $stateId)->update($data);
        	}
        }
      
    }
}
