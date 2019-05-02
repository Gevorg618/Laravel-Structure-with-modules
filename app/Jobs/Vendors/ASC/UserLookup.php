<?php

namespace App\Jobs\Vendors\ASC;

use App\Jobs\Job;
use ASCLicenses\Licenses;
use App\Models\Users\User;
use App\Models\Management\ASCLicense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Management\UserASCLicense;
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
            $licenses = $user->ascLicenses($this->state);

            if(!$licenses->count()) {
                continue;
            }
            
            foreach($licenses as $license) {
                UserASCLicense::firstOrNew([
                        'user_id' => $user->id,
                        'state' => $this->state,
                        'license_number' => $license->lic_number
                ])
                ->fill([
                    'asc_id' => $license->id,
                    'user_id' => $user->id,
                    'state' => $this->state,
                    'license_number' => $license->lic_number,
                    'expiration' => $license->exp_date,
                    'license_type' => $license->lic_type
                ])
                ->save();
            }
        }
    }
}
