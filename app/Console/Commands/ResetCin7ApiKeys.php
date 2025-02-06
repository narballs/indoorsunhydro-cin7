<?php

namespace App\Console\Commands;

use App\Models\AdminSetting;
use App\Models\ApiEndpointRequest;
use App\Models\ApiKeys;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetCin7ApiKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:cin7_api_keys';

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
        try {


            $cin7_api_key1  = AdminSetting::where('option_name', 'cin7_auth_password')->first();
            $cin7_api_key2  = AdminSetting::where('option_name', 'cin7_auth_password_2')->first();
            $cin7_api_username  = AdminSetting::where('option_name', 'cin7_auth_username')->first();

            $ApiKeys = ApiKeys::all();
            if ($ApiKeys->count() > 0) {
                foreach ($ApiKeys as $apiKey) {
                    $apiKey->is_active = 0;
                    $apiKey->save();
                }
            }
            
            $cin7_api_keys = [
                [
                    'name' => 'Cin7 Key 1', 
                    'username' => !empty($cin7_api_username) ? $cin7_api_username->option_value : '', 
                    'password' => !empty($cin7_api_key1) ? $cin7_api_key1->option_value : '',
                    'threshold' => 5000,
                    'request_count' => 0,
                    'is_active' => true,
                    'is_stop' => false,
                ],
                [
                    'name' => 'Cin7 Key 2', 
                    'username' => !empty($cin7_api_username) ? $cin7_api_username->option_value : '', 
                    'password' => !empty($cin7_api_key2) ? $cin7_api_key2->option_value : '',
                    'threshold' => 5000,
                    'request_count' => 0,
                    'is_active' => true,
                    'is_stop' => false,
                ],
            ];

            foreach ($cin7_api_keys as $cin7_api_key) {
                ApiKeys::create($cin7_api_key);
            }
        } catch (\Exception $e) {
            Log::error('Error in Cin7 API key processing: ' . $e->getMessage());
        }
    }

}
