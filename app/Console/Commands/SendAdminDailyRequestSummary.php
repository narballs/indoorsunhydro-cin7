<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use Illuminate\Console\Command;
use App\Models\AdminStockReportSetting;
use App\Models\ProductStockNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendAdminDailyRequestSummary extends Command
{
    protected $signature = 'report:daily-user-stock-requests';
    protected $description = 'Send a daily summary email to admins about user stock requests from the previous day';

    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        // Fetch stock requests created yesterday and still pending (status 0)
        $stock_requests = ProductStockNotification::with('product', 'product.options')
            ->where('status', 0)
            ->whereDate('created_at', $yesterday)
            ->get();

        if ($stock_requests->isEmpty()) {
            $this->info("No stock requests found for {$yesterday}.");
            return;
        }

        $setting = AdminStockReportSetting::first();

        if (!$setting || empty($setting->emails)) {
            $this->warn('No admin emails found in AdminStockReportSetting.');
            return;
        }

        $emailsArray = json_decode($setting->emails, true);
        $admin_emails = [];

        if (is_array($emailsArray)) {
            foreach ($emailsArray as $emailObj) {
                if (isset($emailObj['value'])) {
                    $admin_emails[] = $emailObj['value'];
                }
            }
        }

        foreach ($admin_emails as $email) {
            $email = trim($email);

            // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //     $this->warn("Invalid email skipped: {$email}");
            //     continue;
            // }

            $data = [
                'subject' => "Stock Request Notifications for {$yesterday}",
                'email' => $email,
                'product_stock_notification_users' => $stock_requests,
                'from' => SettingHelper::getSetting('noreply_email_address'),
            ];

            try {
                MailHelper::sendMailNotification('pdf.stock_request_summary', $data);
                $this->info("Email sent to: {$email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$email}: ");
            }
        }

        $this->info("Daily stock request summary sent for {$yesterday}.");
    }
}
