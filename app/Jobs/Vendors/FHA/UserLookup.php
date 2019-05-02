<?php

namespace App\Jobs\Vendors\FHA;

use App\Jobs\Job;
use App\Models\Users\User;
use App\Models\Management\FHALicense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Management\UserFHALicense;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLookup extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $users;
    public $state;

    public function __construct(array $users, $state)
    {
        $this->users = $users;
        $this->state = $state;
    }

    public function handle()
    {
        $users = User::with(['userData'])->whereIn('id', $this->users)->get();

        foreach($users as $user) {
            $licenses = $user->fhaLicenses($this->state);

            if(!$licenses->count()) {
                continue;
            }
            
            foreach($licenses as $license) {
                UserFHALicense::firstOrNew([
                        'user_id' => $user->id,
                        'state' => $this->state,
                        'license_number' => $license->license_number
                ])
                ->fill([
                    'fha_id' => $license->id,
                    'user_id' => $user->id,
                    'state' => $this->state,
                    'license_number' => $license->license_number,
                    'expiration' => $license->expiration,
                    'license_type' => $license->license_type
                ])
                ->save();
            }
        }
    }
}
