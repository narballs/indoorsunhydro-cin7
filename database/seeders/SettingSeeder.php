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
                'option_name' => 'sync_product_options', 
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
                'option_name' => 'enable_stripe_checkout', 
                'type' => 'yes/no',
                'option_value' => 'no',
            ],
            [
                'option_name' => 'cin7_auth_username', 
                'type' => 'text',
                'option_value' => 'IndoorSunHydroUS',
            ],
            [
                'option_name' => 'cin7_auth_password', 
                'type' => 'text',
                'option_value' => 'faada8a7a5ef4f90abaabb63e078b5c1',
            ],
            [
                'option_name' => 'noreply_email_address', 
                'type' => 'text',
                'option_value' => 'noreply@indoorsunhydro.com',
            ],
            [
                'option_name' => 'from_email_name', 
                'type' => 'text',
                'option_value' => 'IndoorSunHydro',
            ],
            [
                'option_name' => 'website_name', 
                'type' => 'text',
                'option_value' => 'Indoor Sun Hydro',
            ],
            [
                'option_name' => 'website_url', 
                'type' => 'text',
                'option_value' => 'https://indoorsunhydro.com',
            ],
            [
                'option_name' => 'store_address_line_1', 
                'type' => 'text',
                'option_value' => '5671 Warehouse Way',
            ],
            [
                'option_name' => 'store_address_line_2', 
                'type' => 'text',
                'option_value' => 'Sacramento CA 95826',
            ],
            [
                'option_name' => 'store_phone_number', 
                'type' => 'text',
                'option_value' => '(916) 281-3090',
            ],
            [
                'option_name' => 'instagram_link', 
                'type' => 'text',
                'option_value' => 'https://www.instagram.com/indoorsunhydro/',
            ],
            [
                'option_name' => 'yelp_link', 
                'type' => 'text',
                'option_value' => 'https://www.yelp.com/biz/indoor-sun-hydro-sacramento-5?osq=indoorsun+hydro',
            ],
            [
                'option_name' => 'facebook_link', 
                'type' => 'text',
                'option_value' => 'https://www.facebook.com/lagardensupply/',
            ],
            [
                'option_name' => 'logo_name', 
                'type' => 'text',
                'option_value' => 'indoor-sun-hydro-logo.png',
            ],
            [
                'option_name' => 'email_logo_name', 
                'type' => 'text',
                'option_value' => 'email_template.png',
            ],
            [
                'option_name' => 'retail_price_column', 
                'type' => 'text',
                'option_value' => 'retailUSD',
            ],
            [
                'option_name' => 'site_id', 
                'type' => 'text',
                'option_value' => '1',
                'is_visible' => false
            ],
            [
                'option_name' => 'timings_part1', 
                'type' => 'text',
                'option_value' => 'MONDAY-SUNDAY 7 Days'
            ],
            [
                'option_name' => 'timings_part2', 
                'type' => 'text',
                'option_value' => '9AM-5pm',
            ],
            [
                'option_name' => 'check_zipcode', 
                'type' => 'yes/no',
                'option_value' => 'Yes',
            ],
            [
                'option_name' => 'shipping_carrier_code', 
                'type' => 'text',
                'option_value' => 'ups_walleted',
            ],
            [
                'option_name' => 'shipping_service_code', 
                'type' => 'text',
                'option_value' => 'ups_ground',
            ],
            [
                'option_name' => 'shipping_package', 
                'type' => 'text',
                'option_value' => 'package',
            ],
            [
                'option_name' => 'shipment_mode', 
                'type' => 'text',
                'option_value' => 'sandbox',
            ],
            [
                'option_name' => 'update_balance_owing', 
                'type' => 'yes/no',
                'option_value' => 'Yes',
            ],
            [
                'option_name' => 'create_order_in_shipstation', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_wholesale_registration', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
        ];

        foreach($setting as $setting) {

            $admin_setting = AdminSetting::firstOrCreate(
                [
                    'option_name' => $setting['option_name']
                ],
                [
                    'type' => $setting['type'], 
                    'option_value' => $setting['option_value'],
                    'is_visible' => (isset($setting['is_visible']) && $setting['is_visible'] == false) ? false : true
                ]
            );
        }
    }
}
