<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->truncate();
        $fileData = File::get(database_path('seeds/resources/regions.json'));
        $regions = json_decode($fileData);
        $data = [];
        foreach ($regions as $key => $value) {
            $data[$key]['name'] = $value->name;
        }
        DB::table('regions')->insert($data);
    }
}
