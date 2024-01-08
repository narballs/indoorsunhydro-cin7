<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\SyncAPiData::class,
        Commands\DownloadAndSaveImage::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        // Api endpoints starts here
        
        $schedule->command('Sync:ApiData')->hourly();
        $schedule->command('Sync:ProductOptions')->hourly();
        
        $schedule->command('sync:supplier')->hourly();
        $schedule->command('AutoOrder:Sync')->everyThreeMinutes();
        $schedule->command('check:orderstatus')->everyThreeHours();

        // $schedule->command('Delete:ContactsPermanently')->daily();

        // Disabling for now
        //$schedule->command('Sync:Stock')->hourly();
        // Api endpoints ends here


        // Internal endpoints starts here

        $schedule->command('ContactsTo:Users')->hourly();
        $schedule->command('Assign:UserToContacts')->hourly();

        // Internal endpoints ends here
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
