<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\AdminSetting;
use Illuminate\Console\Command;
use App\Models\Contact; // adjust to your actual model name
use App\Models\InvalidAddressUser;
use Illuminate\Support\Facades\Mail;

class SendInvalidContactsSummary extends Command
{
    protected $signature = 'contacts:send-invalid-contacts-summary';
    protected $description = 'Send a summary email to the admin with invalid contacts';

    public function handle()
    {
        
        $enable_setting  = AdminSetting::where('option_name', 'enable_invalid_contacts_summary_email')->first();

        if (!$enable_setting || strtolower($enable_setting->option_value) != 'yes') {
            $this->info("Invalid contacts summary email is disabled.");
            return;
        }
        
        
        $invalid_contacts = InvalidAddressUser::where('summary_sent', false)
            ->get();
        if ($invalid_contacts->isEmpty()) {
            $this->info("No invalid contacts found.");
            return;
        }


        
        $email_addresses = array_filter([
            SettingHelper::getSetting('invalid_contacts_summary_danish_email'),
            SettingHelper::getSetting('invalid_contacts_summary_naris_email'),
        ]);
        
        foreach ($email_addresses as $email) {
            $data = [
                'subject' => 'Invalid Contacts Summary',
                'email' => $email,
                'invalid_contacts' => $invalid_contacts,
                'from' => SettingHelper::getSetting('noreply_email_address'),
            ];

            try {
                MailHelper::sendMailNotification('emails.invalid_contacts_summary', $data);
                $this->info("Email sent to: {$email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$email}: " . $e->getMessage());
            }
        }

        $this->stock_summary_sent($invalid_contacts);
    }


    private function stock_summary_sent($invalid_contacts)
    {
        foreach ($invalid_contacts as $contact) {
            try {
                $contact->summary_sent = true;
                $contact->save();
            } catch (\Exception $e) {
                $this->error("Failed to update summary_sent for ID {$contact->id}: " . $e->getMessage());
            }
        }
    }
}
