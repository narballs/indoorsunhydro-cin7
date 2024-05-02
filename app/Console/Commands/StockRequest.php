<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\ProductStockNotification;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
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
        $product_stock_notification_users = ProductStockNotification::with('product', 'product.options')->where('status', 0)->get();

        if (count($product_stock_notification_users) === 0) {
            $this->info('No stock request notifications found.');
            return 0;
        }

         // Send email
        $this->sendEmail($product_stock_notification_users);

        $this->info('Stock request notifications sent successfully.');

    }

    private function sendEmail($product_stock_notification_users) {
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id')->toArray();
        $users_with_role_admin = User::select("email")->whereIn('id', $admin_users)->get();

        if ($users_with_role_admin->isNotEmpty()) {
            foreach ($users_with_role_admin as $role_admin) {
                $subject = 'Stock Request Notifications';
                $data = [
                    'subject' => $subject,
                    'email' => $role_admin->email,
                    'product_stock_notification_users' => $product_stock_notification_users,
                    'from' => SettingHelper::getSetting('noreply_email_address'),
                ];
                MailHelper::sendMailNotification('pdf.stock_request', $data);
            }
        }
    }
}
