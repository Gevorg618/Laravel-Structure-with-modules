<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\State;
class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = collect(getStates());
        $states = $states->map(function($item, $it) {
            return ['abbr' => $it,'state' => $item];
        });
        foreach ($states as $state) {
            State::create($state);
        }
    }
}
