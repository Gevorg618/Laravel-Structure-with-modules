<?php

namespace App\Console\Commands\Vendors;

use Exception;
use App\Models\Users\User;
use App\Models\Users\UserData;
use Illuminate\Console\Command;
use App\Jobs\Vendors\ASC\Import as ASCImportJob;

class ASCImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendors:import-asc-licenses {--state=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the entire asc.gov appraiser list (or only one state)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // If we passed in a state make sure it's valid
        $states = getStates(true);
        $state = $this->option('state');

        if($state && !in_array($state, $states)) {
            $this->error(sprintf("Invalid State specifed %s", $state));
            return;
        }

        // Loop each state and add a job to import the ASC licenses
        if($state) {
            $statesToRun = [$state];
        } else {
            $statesToRun = $states;
        }

        foreach($statesToRun as $r) {
            $this->info(sprintf("Importing %s", $r));
            dispatch(new ASCImportJob($r))->onQueue('low');
        }

        $this->info('Operation Completed.');
    }
}
