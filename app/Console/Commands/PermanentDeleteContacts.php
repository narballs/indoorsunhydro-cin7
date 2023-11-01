<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\User;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\DB;



class PermanentDeleteContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Delete:ContactsPermanently';

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
        $empty_trash_days = AdminSetting::where('option_name', 'empty_trash_time_for_contacts')->first();
        $empty_trash_days_value = $empty_trash_days->option_value;
        
        if (!empty($empty_trash_days_value)) {
            $contacts = Contact::onlyTrashed()->where('deleted_at', '<=', now()->subDays($empty_trash_days_value))->get();
            $users = User::onlyTrashed()->where('deleted_at', '<=', now()->subDays($empty_trash_days_value))->get();
            if (count($contacts) > 0) {
                $this->info('Processing contacts...');
                foreach ($contacts as $contact) {
                    $contact->forceDelete();
                }
            } else {
                $this->info('No contacts to delete.');
            }

           
            if (count($users) > 0) {
                $this->info('Processing users...');
                foreach ($users as $user) {
                    $user->forceDelete();
                }
            } else {
                $this->info('No users to delete.');
            }
        }
    }
}
