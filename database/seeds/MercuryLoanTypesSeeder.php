<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\MercuryNetwork\MercuryLoanType;

class MercuryLoanTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * The key in array is unique and it isn't changeable
     * If You want to add row add it in the end, because they display in alphabetical order  
     * @return void
     */
    public function run()
    {
        $loanTypes = [
	        1 => 'Conventional',
	        2 => 'FHA',
	        3 => 'Purchase',
	        4 => 'Refinance',
	        5 => 'VA',
	        6 => 'Other',
	        7 => 'FSA/RHS/FmHA',
	        8 => 'CMHC',
	        9 => 'USDA',
	        10 => 'Reverse Mortgage',
	        11 => 'Home Ownership Accelerator',
	        12 => 'FHA 203K',
	        13 => 'HARP 2',
	        14 => 'Home Equity',
	        15 => 'Jumbo',
	        16 => 'HomeStyle Renovation',
	        17 => 'Alt QM Agency',
	        18 => 'Alt QM Income',
	        19 => 'Alt QM Jumbo',
	        20 => 'Alt QM Investor',
	        21 => 'FHA 203K Full',
	        22 => 'All In One',
	        23 => 'One Time Close',
	        24 => 'Alt QM Investor',
	        25 => 'CMBS',
	        26 => 'Commercial - other',
	        27 => 'Construction financing',
	        28 => 'Mezzanine financing',
	        29 => 'SBA 7A',
	        30 => 'SBA 504',
	        31 => 'FHA 223(f)',
	        32 => 'FHA 203(k) Standard',
	        33 => 'FHA 203(k) Limited',
	        34 => 'Section 184 Native American Loan',
	        35 => 'REO',
	    ];

	    foreach ($loanTypes as $key => $value) {
	    	MercuryLoanType::updateOrCreate(
	    		[ 'external_id' => $key ],
	    		[ 'title' => $value ]
	    	);
	    }
    }
}
