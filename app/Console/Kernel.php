<?php

namespace App\Console;

use App\Models\AdminStockReportInterval;
use App\Models\AutoLabelSetting;
use App\Models\LabelLog;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        // $schedule->command('stock:checking')->everyFiveMinutes();
        $schedule->command('stock:checking')
            ->everyFiveMinutes()
            ->between('9:00', '17:59');

        $schedule->command('stock:checking')
            ->everyThirtyMinutes()
            ->between('18:00', '23:59');
            
        $schedule->command('auto:notify')->everyThreeMinutes();
        // $schedule->command('admin:stockrequest')->weekly();
        $schedule->command('sync:payouts')->daily();
        $schedule->command('sync:gmc')->hourly();
        $schedule->command('sync:ai_suggested_prices')->hourly();
        // $schedule->command('update:lags-products')->hourly();
        $schedule->command('ContactsTo:Users')->hourly();
        $schedule->command('Assign:UserToContacts')->hourly();
        $schedule->command('reset:cin7_api_keys')->dailyAt('00:00');
        
        $autoLabelSetting = AutoLabelSetting::first();

        if ($autoLabelSetting) {
            $daysOfWeek = json_decode($autoLabelSetting->days_of_week, true);

            foreach ($autoLabelSetting->timeRanges as $timeRange) {
                $startTime = Carbon::parse($timeRange->start_time)->format('H:i');
                $endTime = Carbon::parse($timeRange->end_time)->format('H:i');

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
                                'Sat' => 'ST',
                                'Sun' => 'SU'
                            ];

                            $currentTime = now()->format('H:i');
                            $mappedDay = strtoupper($dayMap[$currentDay]);
                            $expectedDay = strtoupper(trim($day));

                            // Log::info("Checking for {$expectedDay}: {$startTime} - {$endTime}");
                            // Log::info("Current Day: {$currentDay} ({$mappedDay}) | Expected: {$expectedDay}");
                            // Log::info("Current Time: {$currentTime} | Scheduled: {$startTime} - {$endTime}");

                            LabelLog::create([
                                'description' => "Checking for {$expectedDay}: {$startTime} - {$endTime}"
                            ]);

                            return $mappedDay == $expectedDay && ($currentTime >= $startTime && $currentTime < $endTime);
                        })
                        ->everyMinute();
                }
            }
        }


        // $schedule->command('mark:order_shipped')->everyThirtyMinutes();
                        


        // Internal endpoints ends here


        // send daily summary email to admins about user stock requests

       

        $stock_interval_summary_times = AdminStockReportInterval::all();

        if ($stock_interval_summary_times->isEmpty()) {
            // Fallback if no intervals found
            $schedule->command('report:daily-user-stock-requests')
                ->dailyAt('09:00')
                ->name('daily-stock-report-default')
                ->withoutOverlapping();
            return;
        }

        foreach ($stock_interval_summary_times as $interval) {
            if (!empty($interval->report_time)) {
                try {
                    $formattedTime = Carbon::createFromFormat('H:i:s', $interval->report_time)->format('H:i');

                    $schedule->command('report:daily-user-stock-requests')
                        ->dailyAt($formattedTime)
                        ->name('daily-stock-report-' . str_replace(':', '-', $formattedTime))
                        ->withoutOverlapping();

                } catch (\Exception $e) {
                    Log::error("Invalid report_time format in AdminStockReportInterval ID {$interval->id}: {$interval->report_time}");
                }
            }
        }

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
