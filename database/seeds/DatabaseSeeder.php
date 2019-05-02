<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(StatesSeeder::class);
        $this->call(FNCStatusesSeeder::class);
        $this->call(FNCApprTypesSeeder::class);
        $this->call(FNCLoanReasonSeeder::class);
        $this->call(FNCLoanTypesSeeder::class);
        $this->call(FNCContactTypesSeeder::class);
        $this->call(FNCPropertyTypesSeeder::class);
        $this->call(MercuryApprTypesSeeder::class);
        $this->call(MercuryColumnMapSeeder::class);
        $this->call(MercuryLoanReasonSeeder::class);
        $this->call(MercuryLoanTypesSeeder::class);
        $this->call(MercuryStatusesSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(TimezonesTableSeeder::class);
        $this->call(UpdateStatesTimezoneRegionSeeder::class);
        $this->call(AdjacentStatesTableSeeder::class);
        $this->call(MercuryApprTypesSeeder::class);
        $this->call(MercuryColumnMapSeeder::class);
        $this->call(MercuryLoanReasonSeeder::class);
        $this->call(MercuryLoanTypesSeeder::class);
        $this->call(MercuryStatusesSeeder::class);
    }
}
