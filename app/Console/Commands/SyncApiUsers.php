<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiUser;

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


        // Find total category pages
        //$total_products_pages = 44;
        // echo env('API_USER'); 
        // echo env('API_PASSWORD');
        // echo 'here';
        // exit;
        $total_users_pages = 4;

        for ($i = 1; $i <= $total_users_pages; $i++) {
            $this->info('Processing page#' . $i);

            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Users/?page=' . $i,
                //'https://api.cin7.com/api/v1/Contacts/9888', 
                [
                    'auth' => [
                        'IndoorSunHydroUS', 
                        'faada8a7a5ef4f90abaabb63e078b5c1'
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
