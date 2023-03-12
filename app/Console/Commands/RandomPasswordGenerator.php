<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\User;
use \Illuminate\Support\Str;
use \Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Helpers\MailHelper;


class RandomPasswordGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Random:PasswordGenerator';

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
            foreach($users as $user) {
                $plain_password = Str::random(10) . date('YmdHis');

                $encrypted_password = bcrypt($plain_password);

                $hash = Str::random(10000) . $user->first_name . date('YmdHis');
                $hash = md5($hash);


                $user->password = $encrypted_password;
                $user->new_password = $plain_password;
                $user->hash = $hash;
                $user->save();
                // if ($user->email == 'acozy88@gmail.com') {
                //     $data['name'] = 'Waqas Zeeshan';
                //     $data['email'] = 'wqszeeshan@gmail.com';
                //     $data['content'] = 'Password Reset';
                //     $data['subject'] = 'Password Reset';
                //     $data['from'] = env('MAIL_FROM_ADDRESS');
                //     $data['plain'] = $plain_password;
                //     MailHelper::sendMailNotification('emails.reset-password', $data);
                // }
            }
            
            // $contacts = Contact::all();
            // foreach($contacts as $contact) {
            //    $email = $contact->email;
            //    if ($email) {
            //         $user = User::firstOrCreate([
            //             'email' => $email
            //         ]);
            //     } 
            // }
        }
}
