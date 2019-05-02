<?php

namespace Modules\Admin\Console\Commands\GeoCode;

use DB;
use Illuminate\Console\Command;

class GeoCodeProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:geocode:profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Profile Geo Code';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $list = DB::table('appr_order')
              ->select(DB::raw("CONCAT(propaddress1,', ',propcity,', ',propstate,' ',propzip) as address"))
              ->orderBy('id', 'DESC')
              ->where('propaddress1', '!=', '')
              ->take(50)
              ->get();

      $time_start = microtime(true); 

      foreach($list as $r) {
        $this->info($r->address);
        $re = geoCode($r->address);
        print_r($re);
      }

      $this->info('Total execution time in seconds: ' . (microtime(true) - $time_start));
    }
}
