<?php

namespace App\Jobs\Vendors\ASC;

use App\Jobs\Job;
use App\Models\Users\User;
use App\Jobs\Vendors\ASC\UserLookup;
use App\Models\Management\ASCLicense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLookupDistributor extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $state;

    public function __construct($state)
    {
        $this->state = $state;
    }

    public function handle()
    {
        User::joinUserData()
            ->ofState($this->state)
            ->appraisers()
            ->orderBy('id')
            ->chunk(20, function($users) {
                dispatch(new UserLookup($users->pluck('id')->all(), $this->state))->onQueue('low');
            });
    }
}
