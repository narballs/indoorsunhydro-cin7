<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\AdminSetting;
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
            if ($stock_notification->product->options[0]->stockAvailable > 0) {
                $data = [
                    'email' => $stock_notification->email,
                    'product' => $stock_notification->product,
                    'product_options' => $stock_notification->product->options,
                    'subject' => 'Product Stock Notification',
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];
    
                $mail = MailHelper::stockMailNotification('emails.user-stock-notification', $data);
            }
            
        }

        $this->info('Finished.');
    }
}
