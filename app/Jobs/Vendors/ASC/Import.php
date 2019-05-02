<?php

namespace App\Jobs\Vendors\ASC;

use App\Jobs\Job;
use ASCLicenses\Licenses;
use Illuminate\Support\Facades\Redis;
use App\Models\Management\ASCLicense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Vendors\ASC\UserLookupDistributor;
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
                        ->all($this->state);

        foreach($records as $record) {
            $record = collect($record)->transform(function($value, $key) {
                if(in_array($key, ['lname', 'fname', 'street', 'city', 'company', 'county'])) {
                    $value = ucwords(strtolower($value));
                } else if($key == 'status') {
                    $value = $value === 'A' ? 1 : 0;
                }

                return $value;
            })->all();

            // Insert or update based on if we have this license number and state combination already
            ASCLicense::firstOrNew(['st_abbr' => $record['st_abbr'], 'lic_number' => $record['lic_number']])->fill($record)->save();
        }
        
        dispatch(new UserLookupDistributor($this->state))->onQueue('low');
    }
}
