<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiUser;

use App\Helpers\SettingHelper;

class SyncApiUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:ApiUsers';

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
         $client2 = new \GuzzleHttp\Client();
        
        $total_users_pages = 4;

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        for ($i = 1; $i <= $total_users_pages; $i++) {
            $this->info('Processing page#' . $i);

            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Users/?page=' . $i,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]
                ]
            );

            $api_users = $res->getBody()->getContents();
            $api_users = json_decode($api_users);

            foreach($api_users as $api_user) {
                $this->info($api_user->id);
                $this->info('---------------------------------------');
                $this->info('Processing users ' . $api_user->firstName);
                $this->info('---------------------------------------');
                $user_data = [
                    'user_id' => $api_user->id,
                    'isActive' => $api_user->isActive,
                    'firstName' => $api_user->firstName,
                    'lastName' => $api_user->lastName,
                    'jobTitle' => $api_user->jobTitle,
                    'email' => $api_user->email
                ];

                $user = ApiUser::firstOrCreate(
                    [
                        'id' => $api_user->id,
                    ],
                    $user_data 
                );
            }
        }
    }
}
