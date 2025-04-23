<?php

namespace App\Console\Commands;

use App\Helpers\LabelHelper;
use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UserHelper;
use App\Models\AdminSetting;
use App\Models\ApiOrder;
use App\Models\AutoLabelSetting;
use App\Models\Contact;
use App\Models\ShipstationApiLogs;
use App\Models\SpecificAdminNotification;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Google\Service\Calendar\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCreateLabel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:label';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a new label for the user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    

    public function handle()
    {
        try {
            $check_label_settings = AdminSetting::where('option_name', 'auto_create_label')->first();

            if (!empty($check_label_settings) && strtolower($check_label_settings->option_value) == 'no') {
                $this->info('Auto create label is disabled');
                return;
            }

            // Log::info('Auto create label is enabled');

            $autoLabelSetting = AutoLabelSetting::first();

            $delay_duration = !empty($autoLabelSetting->delay_duration) ? $autoLabelSetting->delay_duration : 0;

            $currentDate = date('Y-m-d');
            $data = [];
            $client = new \GuzzleHttp\Client();

            $all_orders = ApiOrder::where('is_shipped', 0)
                ->where('label_created', 0)
                ->where('is_stripe', 1)
                ->where('shipment_price', '>', 0)
                ->whereNotNull('shipstation_orderId')
                ->where('payment_status', 'paid')
                ->where('isApproved', 1)
                ->where('created_at', '>=', '2025-01-09 12:23:51')
                ->where('shipping_carrier_code', 'ups_walleted')
                ->get();

            

            if ($autoLabelSetting) {
                $daysOfWeek = json_decode($autoLabelSetting->days_of_week, true);
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
            
                // Log::info("Configured days: " . implode(',', $daysOfWeek));
            
                foreach ($autoLabelSetting->timeRanges ?? [] as $timeRange) {
                    if (empty($timeRange) || !isset($timeRange->start_time, $timeRange->end_time)) {
                        Log::warning('Invalid time range found.');
                        continue;
                    }
            
                    $startTime = Carbon::parse($timeRange->start_time)->format('H:i');
                    $endTime = Carbon::parse($timeRange->end_time)->format('H:i');
                    $currentTime = now()->format('H:i');
            
                    // Log::info("Checking schedule for day: " . $dayMap[$currentDay] . 
                    //         " | Start: {$startTime} | End: {$endTime} | Current: {$currentTime}");
            
                    if (in_array(strtoupper($dayMap[$currentDay]), array_map('strtoupper', $daysOfWeek))) {
                        if ($currentTime >= $startTime && $currentTime <= $endTime) {
                            if ($all_orders->isEmpty()) {
                                $this->info('No orders found to create label');
                                Log::info('No orders found to process.');
                                return;
                            }
            
                            // Log::info('Auto create label is enabled');
            
                            foreach ($all_orders as $order) {

                                $ship_station_log = ShipstationApiLogs::where('order_id', $order->id)
                                    ->where('action', 'create_label')
                                    ->first();

                                if (!empty($ship_station_log)) {
                                    Log::info('Label already created for order: ' . $order->id);
                                    continue;
                                }    
                                
                                if ($order->label_created === 1) {
                                    Log::info('Label already created for order: ' . $order->id);
                                    continue;
                                }

                                LabelHelper::processOrder($order, $client, $currentDate, $data);
                                Log::info('Label created for order: ' . $order->id);
                                $this->info('Label created for order: ' . $order->id);
                            }
                        }
                    }
                }
            } else {
                $this->info('Auto create label is disabled');
                Log::info('Auto label setting not found.');
            }

        } catch (\Exception $e) {
            ShipstationApiLogs::create([
                'api_url' => 'auto_create_label',
                'action' => 'auto_create_label_error',
                'request' =>'create label',
                'response' => $e->getMessage(),
                'order_id' => null,
                'status' => 500,
            ]);
        }
    }
}
