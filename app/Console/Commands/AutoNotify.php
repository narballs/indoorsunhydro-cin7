<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\AdminSetting;
use App\Models\DailyApiLog;
use App\Models\ProductStockNotification;
use Illuminate\Console\Command;

class AutoNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $notification_count = 0;
        $option = AdminSetting::where('option_name', 'auto_notify')->first();
        if (!empty($option->option_value) && strtolower($option->option_value) == 'no') {
            return false;
        }

        $product_stock_notification = ProductStockNotification::with('product' , 'product.options')->where('status' , 0)->get();

        if (empty($product_stock_notification) || count($product_stock_notification) == 0) {
            $this->info('There are no notifications  about stock to process.');
            return false;
        }

        foreach ($product_stock_notification as $stock_notification) {
            $update_stock_notification = ProductStockNotification::find($stock_notification->id);
            if ($stock_notification->product->options[0]->stockAvailable > 0) {
                $data = [
                    'email' => $stock_notification->email,
                    'product' => $stock_notification->product,
                    'product_options' => $stock_notification->product->options,
                    'subject' => 'Product Stock Notification',
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];
    
                $mail = MailHelper::stockMailNotification('emails.user-stock-notification', $data);
                if ($mail) {
                    $update_stock_notification->is_notified = 1;
                    $update_stock_notification->status = 1;
                    $update_stock_notification->save();

                    $notification_count += $stock_notification->cont();
                }
            }
            
        }

        $daily_api_log = DailyApiLog::where('date', date('Y-m-d'))
            ->where('api_endpoint', 'Stock Notification to users from indoorsun')
            ->first();
        
        if (empty($daily_api_log)) {
            $daily_api_log = new DailyApiLog();
            $daily_api_log->date = date('Y-m-d');
            $daily_api_log->api_endpoint = 'Stock Notification to users from indoorsun';
            $daily_api_log->count = $notification_count;
            $daily_api_log->save();
        } else {
            $daily_api_log->count = $daily_api_log->count + $notification_count;
            $daily_api_log->save();
        }

        $this->info('Finished.');
    }
}
