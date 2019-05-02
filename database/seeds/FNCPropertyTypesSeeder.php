<?php

use Illuminate\Database\Seeder;
use App\Models\Integrations\FNC\FNCPropertyTypes;

class FNCPropertyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $propertyTypes = [
            'CNDHO' => 'Condo Hotel',
            'COMM' => 'Commercial',
            'CONDO' => 'Condominium',
            'COOP' => 'Cooperative',
            'DPLX2' => 'Duplex - 2 Unit',
            'DPLX3' => 'Duplex - 3 Unit',
            'DPLX4' => 'Duplex - 4 Unit',
            'HIRIS' => 'High Rise',
            'LORIS' => 'Low Rise',
            'LOT' => 'Lot',
            'MANU' => 'Manufactured',
            'MOBLE' => 'Mobile Home',
            'PUD' => 'PUD',
            'SFR' => 'SFR Attached',
            'SFRD' => 'SFR Detached',
            'VACNT' => 'Vacant Land',
        ];
        
        foreach ($propertyTypes as $key => $value) {
            FNCPropertyTypes::updateOrCreate(
                [ 'key' => $key ],
                [ 'value' => $value ]
            );
        }
    }
}
