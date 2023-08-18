<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminSetting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting =  
        [
            [
                'option_name' => 'auto_full_fill', 
                'type' => 'boolean',
                'option_value' => 0,
            ],
            [
                'option_name' => 'check_product_stock', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_sign_up', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'sync_api_data', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'allow_order_without_stock', 
                'type' => 'yes/no',
                'option_value' => 'no',
            ],
            [
                'option_name' => 'out_of_stock_label', 
                'type' => 'text',
                'option_value' => 'On Back Order',
            ],
            [
                'option_name' => 'enable_stripe_chekout', 
                'type' => 'yes/no',
                'option_value' => 'no',
            ]

        ];

        foreach($setting as $setting) {

            $admin_setting = AdminSetting::firstOrCreate(
                [
                    'option_name' => $setting['option_name']
                ],
                [
                    'type' => $setting['type'], 
                    'option_value' => $setting['option_value']
                ]
            );
        }
    }
}
