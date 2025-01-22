<?php

namespace Database\Seeders;

use App\Models\AdminSetting;
use App\Models\ApiKeys;
use Illuminate\Database\Seeder;

class ApikeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $cin7_api_key1  = AdminSetting::where('option_name' , 'cin7_auth_password')->first();
        $cin7_api_key2  = AdminSetting::where('option_name' , 'cin7_auth_password_2')->first();
        $cin7_api_username  = AdminSetting::where('option_name' , 'cin7_auth_username')->first();
        
        $cin7_api_keys =  
            [
                [
                    'name' => 'Cin7 Key 1', 
                    'username' => !empty($cin7_api_username) ? $cin7_api_username->option_value : '', 
                    'password' => !empty($cin7_api_key1) ? $cin7_api_key1->option_value : '',
                    'threshold' => 4000,
                    'request_count' => 0,
                    'is_active' => true,
                ],

                [
                    'name' => 'Cin7 Key 2', 
                    'username' => !empty($cin7_api_username) ? $cin7_api_username->option_value : '', 
                    'password' => !empty($cin7_api_key2) ? $cin7_api_key2->option_value : '',
                    'threshold' => 4000,
                    'request_count' => 0,
                    'is_active' => true,
                ],
       
            ];
        foreach($cin7_api_keys as $cin7_api_key) {
            ApiKeys::create($cin7_api_key);
        }
    }
}
