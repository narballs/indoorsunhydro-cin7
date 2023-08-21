<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\User;


class UserToContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Assign:UserToContacts';

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
        User::chunk(100, function($users) {
            foreach ($users as $user) {

                $this->counter++;
                $this->info($this->counter . ' => Processing email: ' . $user->email);

                $user_id = $user->id;
                
                if ($user_id) {
                    $contacts = Contact::where('email', $user->email)->get();
                    foreach ($contacts as $contact) {
                        $contact->user_id = $user_id;
                        $contact->save();
                    }
                } 
            }
        });


        $this->info($this->counter . ' Finished.');
    }
}
