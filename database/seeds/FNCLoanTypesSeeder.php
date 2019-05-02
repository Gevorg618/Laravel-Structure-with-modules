<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\FNC\FNCLoanTypes;

class FNCLoanTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loanTypes = [
            '203' => '203(k)',
            'FHA' => 'FHA',
            'USDA' => 'USDA',
            'VA' => 'VA',
        ];

        foreach ($loanTypes as $key => $value) {
            FNCLoanTypes::updateOrCreate(
                [ 'key' => $key ],
                [ 'value' => $value ]
            );
        }
    }
}
