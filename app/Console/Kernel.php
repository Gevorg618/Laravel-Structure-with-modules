<?php

namespace App\Console;

use App\Console\Commands\GenerateInvoice;
use App\Console\Commands\PrintInvoice;
use App\Console\Commands\Setup\AdminUser;
use App\Console\Commands\ImportTicket;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Vendors\ASCImport;
use App\Console\Commands\Vendors\FHAImport;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ASCImport::class,
        FHAImport::class,
        
        AdminUser::class,
        ImportTicket::class,
        GenerateInvoice::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('clean:directories')->daily();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
        
        $schedule->command('vendors:import-asc-licenses')->twiceDaily(3, 12);
        $schedule->command('vendors:import-fha-licenses')->twiceDaily(4, 13);

        $schedule->command(ImportTicket::class)
            ->cron('*/2 * * * * *')
            ->withoutOverlapping()
            ->sendOutputTo(sprintf('storage/logs/%s/import_tickets.log', date('m_d_y_h')));
    }
}
