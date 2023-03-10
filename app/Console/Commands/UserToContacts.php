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
            $users = User::all();
            foreach($users as $user){
                $axisting_email = $user->email;
                $contact = Contact::where('email',$axisting_email)->get();
                  if(!empty($contact)){
                   foreach ($contact as $data){
                    $data->user_id = $user->id;
                     $data->save();
                   }
                }
            }
        }
}
