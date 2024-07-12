<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\NewsletterSubscription;

class ImportContactsToSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:subscribers';

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
        $contacts = Contact::all();
        if (count($contacts) == 0) {
            $this->info('No contacts found to import');
            return;
        }
        foreach ($contacts as $contact) {
            $check_subscriber = NewsletterSubscription::where('email', $contact->email)->first();
            if (!empty($check_subscriber)) {
                continue;
            }
            $subscriber = new NewsletterSubscription();
            $subscriber->email = $contact->email;
            $subscriber->save();
        }

        $this->info('Contacts imported successfully');
    }
}
