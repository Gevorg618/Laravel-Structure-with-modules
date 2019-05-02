<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\MercuryNetwork\MercuryLoanReason;

class MercuryLoanReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * The key in array is unique and it isn't changeable
     * If You want to add row add it in the end, because they display in alphabetical order  
     * @return void
     */
    public function run()
    {
        $loanReason = [
	        1 => 'Construction',
	        2 => 'Construction Permanent',
	        3 => 'Purchase',
	        4 => 'Refinance',
	        5 => 'Other',
	        6 => 'Second Mortgage',
	    ];

        foreach ($loanReason as $key => $value) {
            MercuryLoanReason::updateOrCreate(
                [ 'external_id' => $key ],
                [ 'title' => $value ]
            );
        }
    }
}
