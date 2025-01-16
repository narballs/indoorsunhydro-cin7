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
        
        $schedule->command('Sync:ApiData')->everyTwoHours();
        $schedule->command('Sync:ProductOptions')
        ->everyThreeHours()
        ->when(function () {
            return in_array(\Carbon\Carbon::now()->dayOfWeek, [
                \Carbon\Carbon::FRIDAY, 
                \Carbon\Carbon::SATURDAY, 
                \Carbon\Carbon::SUNDAY
            ]);
        });
        // $schedule->command('get:sale_payments')->hourly()
        // ->when(function () {
        //     return in_array(\Carbon\Carbon::now()->dayOfWeek, [ 
        //         \Carbon\Carbon::SATURDAY, 
        //         \Carbon\Carbon::SUNDAY
        //     ]);
        // });
        $schedule->command('sync:supplier')->everyTwoHours();
        $schedule->command('AutoOrder:Sync')->everyThreeMinutes();
        $schedule->command('check:orderstatus')->everyThreeHours();
        $schedule->command('cancel:order')->everyFourMinutes();
        $schedule->command('stock:checking')->everyFiveMinutes();
        $schedule->command('auto:notify')->everyThreeMinutes();
        $schedule->command('admin:stockrequest')->weekly();
        $schedule->command('sync:gmc')->hourly();
        // $schedule->command('update:lags-products')->hourly();

        // Disabling for now
        //$schedule->command('Sync:Stock')->hourly();
        // Api endpoints ends here


        // Internal endpoints starts here

        $schedule->command('ContactsTo:Users')->hourly();
        $schedule->command('Assign:UserToContacts')->hourly();
        $schedule->command('create:label')->dailyAt('6:00'); // Runs at 5:00 PM
        $schedule->command('create:label')->dailyAt('9:30'); // Runs at 8:30 PM


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
