<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\AdminSetting;
use App\Models\OrderReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOrderReminders extends Command
{
    protected $signature = 'reminders:send-orders';
    protected $description = 'Send reminder emails for orders set by users';

    public function handle()
    {
        
        $enable_reminders = AdminSetting::where('option_name', 'enable_order_reminder')->first();
        if (!$enable_reminders || strtolower($enable_reminders->option_value) != 'yes') {
            $this->info('Order reminders are disabled.');
            return;
        }
        
        $today = Carbon::today();

        $reminders = OrderReminder::with('contact', 'order' , 'order.apiOrderItem' , 'order.apiOrderItem.product', 'order.apiOrderItem.product_option')
            ->whereDate('reminder_date', $today)
            ->where('is_sent', false)
            ->get();

        Log::info("Sending reminders for " . $reminders->count() . " orders for date: " . $today->toDateString());

        $base_url = url('/');

        foreach ($reminders as $reminder) {
            Log::info($reminder->contact);
            if (!empty($reminder->contact) && !empty($reminder->contact->email) && !empty($reminder->order)) {
                $data = [
                    'name' => $reminder->contact->firstName . ' ' . $reminder->contact->lastName,
                    'email' => $reminder->contact->email,
                    'order_id' => $reminder->order_id,
                    'reminder_date' => $reminder->reminder_date,
                    'order_data' => $reminder->order,
                    'link' => $base_url.'/re-order/' . $reminder->order_id,
                    'subject' => 'Reminder: Your Order ' . $reminder->order_id . ' is Ready for Re-Order',
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];  


                
                MailHelper::sendMailNotification('emails.re_order_email_template', $data);

                // Mark the reminder as sent
                $reminder->is_sent = true;
                $reminder->save();

                $this->info("Reminder sent to " . $data['email'] . " for order ID: " . $data['order_id']);
            } else {
                $this->error("No contact email found for reminder ID: " . $reminder->id);
            }
        }
    }
}
