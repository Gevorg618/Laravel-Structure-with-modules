<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\FNC\FNCContactTypes;

class FNCContactTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contactTypes = [
            'E' => 'Entry',
            'O' => 'Owner',
            'B' => 'Borrower',
            'G' => 'General Contact',
            'C' => 'Contact',
            'S' => 'Seller',
            'L' => 'Loan Officer',
            'CB' => 'Co-Borrower',
            'LA' => 'Listing Agent',
            'BA' => 'Buyer\' Agent',
            'BK' => 'Broker',
            'BU' => 'Builder',
        ];

        foreach ($contactTypes as $key => $value) {
            FNCContactTypes::updateOrCreate(
                [ 'key' => $key ],
                [ 'value' => $value ]
            );
        }
    }
}
