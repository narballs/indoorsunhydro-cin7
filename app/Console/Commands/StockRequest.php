<?php

namespace App\Console\Commands;

use App\Helpers\SettingHelper;
use App\Models\ProductStockNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StockRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:stockrequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Request to users from indoorsun';

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
        // Fetch product stock notifications
        $product_stock_notification_users = ProductStockNotification::with('product', 'product.options')->where('status', 0)->take(1)->get();

        // Compose email content
        $emailContent = $this->composeEmailContent($product_stock_notification_users);

        // Send email
        $this->sendEmail($emailContent);

        // Optionally, you can mark notifications as sent
        foreach ($product_stock_notification_users as $notification) {
            $notification->status = 1;
            $notification->save();
        }

        return 0;
    }

    /**
     * Compose email content with tabulated data.
     *
     * @param  \Illuminate\Support\Collection  $notifications
     * @return string
     */
    private function composeEmailContent($notifications)
    {
        $tableRows = [];

        // Add table header
        $tableRows[] = "| Product Name | Date Notification Requested | Current Stock Level as of Date | SKU | User Email |";

        // Add table rows
        foreach ($notifications as $notification) {
            $tableRows[] = "| {$notification->product->name} | {$notification->created_at} | {$notification->product->options[0]->current_stock_level} | {$notification->product->sku} | {$notification->email} |";
        }

        // Join all table rows with newline character
        $table = implode(PHP_EOL, $tableRows);

        // Email content
        $emailContent = "Dear User,\n\nPlease find below the stock request notifications:\n\n$table";

        return $emailContent;
    }

    /**
     * Send email.
     *
     * @param  string  $emailContent
     * @return void
     */
    private function sendEmail($emailContent)
    {
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id')->toArray();

        $users_with_role_admin = User::select("email")->whereIn('id', $admin_users)->get();

        if ($users_with_role_admin->isNotEmpty()) {
            foreach ($users_with_role_admin as $role_admin) {
                Mail::raw($emailContent, function ($message) use ($role_admin) {
                    $message->to($role_admin->email)
                        ->subject('Stock Request Notifications')
                        ->from(SettingHelper::getSetting('noreply_email_address'));
                });
            }
        }
    }
}
