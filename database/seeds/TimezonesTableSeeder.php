<?php

use Illuminate\Database\Seeder;

class TimezonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timezones')->truncate();
        $timeZones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'US');
        $data = [];
        foreach ($timeZones as $key => $timezone) {
            $data[$key]['name'] = $timezone;
        }
        DB::table('timezones')->insert($data);
    }
}
