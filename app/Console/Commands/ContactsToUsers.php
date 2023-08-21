<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\User;
use \Illuminate\Support\Str;
use \Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Helpers\MailHelper;


class ContactsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ContactsTo:Users';

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
        $this->counter = 0;
        
        Contact::chunk(100, function($contacts) {
            foreach ($contacts as $contact)
            {
                $this->counter++;
                $email = $contact->email;
                
                $this->info($this->counter . ' => Processing email: ' . $email);

                if (empty($email)) {
                    $this->error('Empty Email: Skipping');
                    continue;
                }
        
                
                $user = User::firstOrCreate([
                    'email' => $email
                ]);
            }
        });
        
        $this->info($this->counter . ' Finished.');
    }
}
