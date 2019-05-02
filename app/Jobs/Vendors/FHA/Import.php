<?php

namespace App\Jobs\Vendors\FHA;

use App\Jobs\Job;
use FHALicenses\Licenses;
use Illuminate\Support\Facades\Redis;
use App\Models\Management\FHALicense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Vendors\FHA\UserLookupDistributor;
use Symfony\Component\Cache\Simple\RedisCache;

class Import extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $state;

    public function __construct($state)
    {
        $this->state = $state;
    }

    public function handle()
    {
        $records = (new Licenses())
                        ->setCache(new RedisCache(Redis::connection()->client()))
                        ->setState($this->state)
                        ->all();

        foreach($records as $record) {
            $record = collect($record)->transform(function($value, $key) {
                if(in_array($key, ['firstname', 'lastname', 'address', 'city', 'company'])) {
                    $value = ucwords(strtolower($value));
                } else if ($key == 'expiration') {
                    $value = formatDate($value, 'Y-m-d', 'm-d-Y');
                }

                return $value;
            })->all();

            // Insert or update based on if we have this license number and state combination already
            FHALicense::firstOrNew(['state' => $record['state'], 'license_number' => $record['license_number']])->fill($record)->save();
        }
        
        dispatch(new UserLookupDistributor($this->state))->onQueue('low');
    }
}
