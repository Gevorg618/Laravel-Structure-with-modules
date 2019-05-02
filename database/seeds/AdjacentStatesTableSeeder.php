<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\State;

class AdjacentStatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('state_adjacent')->truncate();
       	
       	$adjacentStates = getAdjacentStates();
       	
       	foreach ($adjacentStates as $stateAbbr => $adjacentStatesArray) {
       			
   			$mainState = State::where('abbr', $stateAbbr)->first();

   			if ($mainState) {
   				
   				foreach ($adjacentStatesArray as $adjacentStateAbbr) {
   					
   					$adjacentState = State::where('abbr', $adjacentStateAbbr)->first();

   					if ($adjacentState) {

   						$data = [
       						'state_1' => $mainState->id,
       						'state_2' => $adjacentState->id
       					];

       					DB::table('state_adjacent')->insert($data);	
   					}
   												
   				}	
   			}       			
       	}
    }
    
}
