<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\MercuryNetwork\MercuryColumnMap;

class MercuryColumnMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * The key in array is unique and it isn't changeable
     * If You want to add row add it in the end, because they display in alphabetical order  
     * @return void
     */
    public function run()
    {
        $columnMap = [
	        'mercury_id' => 'TRACKINGID',
	        'mercury_client_id' => 'CLIENTID',
	        'additional_email' => 'ADDITIONAL_NOTIFICATION_RECIPIENTS',
	        'propaddress1' => 'SUBPROPADDRESS',
	        'propcity' => 'SUBCITY',
	        'propstate' => 'SUBSTATE',
	        'propzip' => 'SUBZIP',
	        'sales_price' => 'SUBSALEPRICE',
	        'legal_descrip' => 'SUBLEGALDESCRIP',
	        'loantype' => 'LOAN_TYPE',
	        'loanpurpose' => 'LOAN_PURPOSE',
	        'appr_type' => 'TYPE_OF_APPRAISAL',
	        //'due_date' => 'DUE_DATE',
	        'fha_case' => 'FHA_CASENUMBER',
	        'is_rush' => 'RUSH_ORDER',
	        'lender' => 'SUBLENCLIENT',
	        'loanrefnum' => 'LENDERCASENUMBER',
	        'lenderaddress' => 'LENDERADDRESS1',
	        'lendercity' => 'LENDERCITY',
	        'lenderstate' => 'LENDERSTATE',
	        'lenderzip' => 'LENDERZIP',
	        'borrower' => 'SUBBORROWER',
	        'co_borrower' => 'COBORROWER',
	    ];

	    foreach ($columnMap as $key => $value) {
	    	MercuryColumnMap::updateOrCreate(
	    		[ 'key' => $key ],
	    		[ 'value' => $value ]
	    	);
	    }
    }
}
