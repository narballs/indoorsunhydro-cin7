<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\AdminStockReportInterval;
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
        $stock_interval_summary_times = AdminStockReportInterval::all();

        if ($stock_interval_summary_times->isEmpty()) {
            $formattedTime = '09:00'; // Default time if no intervals are found
            if (Carbon::now()->format('H:i') == $formattedTime) {
                $this->sendStockReportEmail();
            }
        } else {
            foreach ($stock_interval_summary_times as $interval) {
                if (!empty($interval->report_time)) {
                    try {
                        $formattedTime = Carbon::createFromFormat('H:i:s', $interval->report_time)->format('H:i');

                        if (Carbon::now()->format('H:i') == $formattedTime) {
                            $this->sendStockReportEmail();
                        } else {
                            $this->info("Current time does not match the scheduled time: {$formattedTime}");
                        }

                    } catch (\Exception $e) {
                        Log::error("Invalid report_time format in AdminStockReportInterval ID {$interval->id}: {$interval->report_time}");
                    }
                }
            }
        }
    }

    private function sendStockReportEmail()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        $stock_requests = ProductStockNotification::with('product', 'product.options')
            ->where('status', 0)
            ->whereDate('created_at', $yesterday)
            ->where('stock_summary_sent', 0)
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
                $this->error("Failed to send email to {$email}: " . $e->getMessage());
            }
        }

        $this->stock_summary_sent($stock_requests);
        $this->info("Daily stock request summary sent for {$yesterday}.");
    }

    private function stock_summary_sent($product_stock_notification_users)
    {
        foreach ($product_stock_notification_users as $notification) {
            try {
                $notification->stock_summary_sent = 1;
                $notification->save();
            } catch (\Exception $e) {
                $this->error("Failed to update stock_summary_sent for ID {$notification->id}: ");
            }
        }
    }

}
