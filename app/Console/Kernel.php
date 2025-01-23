<?php

namespace App\Console;

use App\Models\AutoLabelSetting;
use Carbon\Carbon;
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
        $schedule->command('cin7_api_keys')->dailyAt('00:00');
        // $schedule->command('create:label')->weekdays()->dailyAt('6:00'); // Runs at 5:00 PM
        // $schedule->command('create:label')->weekdays()->dailyAt('9:30'); // Runs at 8:30 PM


        // Fetch the auto label settings
        $autoLabelSetting = AutoLabelSetting::first();

        if ($autoLabelSetting) {
            // Decode the stored days of the week from the database
            $daysOfWeek = json_decode($autoLabelSetting->days_of_week, true);
        
            // Loop through the time ranges
            foreach ($autoLabelSetting->timeRanges as $timeRange) {
                $startTime = Carbon::parse($timeRange->start_time)->format('H:i');
                $endTime = Carbon::parse($timeRange->end_time)->format('H:i');
        
                // Loop through the days of the week and schedule for each day
                foreach ($daysOfWeek as $day) {
                    $schedule->command('create:label')
                        ->when(function () use ($day, $startTime, $endTime) {
                            $currentDay = now()->format('D'); 
                            $dayMap = [
                                'Mon' => 'M',
                                'Tue' => 'T',
                                'Wed' => 'W',
                                'Thu' => 'TH',
                                'Fri' => 'F',
                                'Sat' => 'S',
                                'Sun' => 'S'
                            ];
                            if (strtoupper($dayMap[$currentDay]) == $day) {
                                $currentTime = now()->format('H:i');
                                return $currentTime >= $startTime && $currentTime <= $endTime;
                            }
        
                            return false;
                        })
                        ->everyMinute();
                }
            }
        }
        


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
