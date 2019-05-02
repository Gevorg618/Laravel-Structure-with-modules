<?php

namespace Modules\Admin\Console\Commands\GeoCode;

use DB;
use Illuminate\Console\Command;
use App\Repositories\Geo\GeoCodingRepository;

class GeoCodeTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:geocode:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test GeoCode';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $repository = new GeoCodingRepository;

      $address = '4132 Breezewood Dr, Wilmington, NC 28412';

      $this->info($address);

      $this->info("Testing GeoCode Helper");
      $result = geoCode($address);
      print_r($result);

      $this->info("Testing Google GeoCode");
      $result = $repository->googleGeoCodeApi($address);
      print_r($result);

      $this->info("Testing Bing GeoCode");
      $result = $repository->bingGeoCodeApi($address);
      print_r($result);

      $this->info('Done.');
    }
}
