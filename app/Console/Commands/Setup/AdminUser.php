<?php

namespace App\Console\Commands\Setup;

use Exception;
use App\Models\Users\User;
use App\Models\Users\UserData;
use Illuminate\Console\Command;

class AdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:admin {--email=admin@admin.com} {--password=$2a$04$7bp6pCKaidwxj1BbHgLSBOw7Bkr2piITB2GnZ8g1iD9yUjyFr4mYq}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating a new admin user.');
        $this->info(sprintf("Email: %s, Password: %s", $this->option('email'), $this->option('password')));

        if ($this->confirm('Do you wish to continue? [y|N]')) {

            try {
                $user = User::create([
                    'email' => $this->option('email'),
                    'password' => $this->option('password'),
                    'user_type' => User::USER_TYPE_ADMIN,
                    'active' => 'Y',
                    'admin_priv' => 'S'
                ]);
    
                UserData::create([
                    'user_id' => $user->id,
                    'firstname' => 'Admin',
                    'lastname' => 'Admin'
                ]);
            } catch(Exception $e) {
                $this->error($e->getMessage());
                return;
            }

            $this->info('User Created.');

        } else {
            $this->error('Operation Aborted.');
        }
    }
}
