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
                'option_name' => 'enable_free_shipping_banner', 
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
                'option_name' => 'cin7_auth_password_2', 
                'type' => 'text',
                'option_value' => '34487efdea814f7892c6b66e7df29f3f',
            ],
            [
                'option_name' => 'cin7_auth_password_3', 
                'type' => 'text',
                'option_value' => '3f40c44c260a46bd9eed53e4f9db5f54',
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
                'option_name' => 'default_price_column', 
                'type' => 'text',
                'option_value' => 'sacramentoUSD',
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
                'option_value' => 'ups_3_day_select',
            ],
            [
                'option_name' => 'shipping_carrier_code_2', 
                'type' => 'text',
                'option_value' => 'seko_ltl_walleted',
            ],
            [
                'option_name' => 'shipping_service_code_2', 
                'type' => 'text',
                'option_value' => 'standard',
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
            [
                'option_name' => 'empty_trash_time_for_contacts', 
                'type' => 'text',
                'option_value' => '10',
            ],
            [
                'option_name' => 'free_shipping_value', 
                'type' => 'text',
                'option_value' => '1000',
            ],
            [
                'option_name' => 'notify_user_about_product_stock', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_announcement_banner', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'announcement_banner_text', 
                'type' => 'text',
                'option_value' => 'Please call to double check inventory if you are planning to place a pick up order',
            ],
            [
                'option_name' => 'square_payment_mode', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'square_payment_access_token', 
                'type' => 'text',
                'option_value' => 'EAAAlx4Z__fo4anToV6YLH4KHDD7bLVf__Jb4d02qX21VapGB-LOwKrquG_WKhBc',
            ],
            [
                'option_name' => 'square_payment_location_id', 
                'type' => 'text',
                'option_value' => 'LN2EBJZEF6NSX',
            ],
            [
                'option_name' => 'square_payment_environment', 
                'type' => 'text',
                'option_value' => 'sandbox',
            ],
            [
                'option_name' => 'square_payment_app_id', 
                'type' => 'text',
                'option_value' => 'sandbox-sq0idb-sK1tlkd_hYyAh7YPaTxolw',
            ],
            [
                'option_name' => 'square_payment_secret', 
                'type' => 'text',
                'option_value' => 'sandbox-sq0csb-ptflX3Fbck0VMfLhDCfPrhx4PD-WDCBHUonJLw48ZAU',
            ],
            [
                'option_name' => 'toggle_registration_approval', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'stock_checking', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'new_checkout_flow', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'auto_notify', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_discount', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'admin_area_for_shipping', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'free_shipping_state', 
                'type' => 'text',
                'option_value' => 'California',
            ],
            [
                'option_name' => 'request_bulk_quantity_discount', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'checkout_issue_banner', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'checkout_issue_banner_text', 
                'type' => 'text',
                'option_value' => 'We have an issue with checkout, our team is investigating it.  The issue will be resolved in 48hrs or less.',
            ],

            [
                'option_name' => 'toggle_shipment_insurance', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'shipment_insurance_fee', 
                'type' => 'text',
                'option_value' => '5.00',
            ],
            [
                'option_name' => 'enable_newsletter', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'twilio_sid', 
                'type' => 'text',
                'option_value' => 'AC0ff2fa526f96986995654144a7f6706b',
            ],
            [
                'option_name' => 'twilio_token', 
                'type' => 'text',
                'option_value' => '670e4e7b83a204a34af1f9c69b722825',
            ],
            [
                'option_name' => 'twilio_number', 
                'type' => 'text',
                'option_value' => '+12512921422',
            ],

            [
                'option_name' => 'pickup_info',
                'type' => 'text',
                'option_value' => 'Pick up is available only at the address below <br/> <strong>5671 Warehouse Way Sacramento CA 95826</strong>
                <br/>
                Pick up window is Monday - Friday (no weekends) <br/>
                All orders are available to be picked up <strong>2 Hours</strong> after the order is placed and paid for.',
            ],

            [
                'option_name' => 'add_extra_70_to_shipping', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'extra_shipping_value', 
                'type' => 'text',
                'option_value' => '70',
            ],

            [
                'option_name' => 'enable_free_shipping_banner_text',
                'type' => 'text',
                'option_value' => 'Good news, your cart qualifies for free shipping',
            ],
            [
                'option_name' => 'add_payment_in_cin7_for_order', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'store_hours_1', 
                'type' => 'text',
                'option_value' => 'Monday-Friday  9:00AM-5:30PM',
            ],
            [
                'option_name' => 'store_hours_2', 
                'type' => 'text',
                'option_value' => 'Saturday-Sunday  9:00AM-5:00PM',
            ],
            [
                'option_name' => 'store_timings_1', 
                'type' => 'text',
                'option_value' => '9:00AM-5:30PM',
            ],
            [
                'option_name' => 'store_timings_2', 
                'type' => 'text',
                'option_value' => '9:00AM-5:00PM',
            ],
            [
                'option_name' => 'enable_ai_prompt', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_see_similar_products', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_gpt-3.5-turbo', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],
            [
                'option_name' => 'enable_cin7_sale_payments', 
                'type' => 'yes/no',
                'option_value' => 'No',
            ],

            [
                'option_name' => 'ai_prompt_text',
                'type' => 'text',
                'option_value' => 'Please provide a concise and structured description relevent to the product using HTML tags where necessary. Keep the response within a reasonable length and formatted. The content should look as it is created in ckeditor. Add bullet points, headings, and other formatting elements as needed. Do not mention about the ckeditor or any other editor.Do not add any kind of suport email , phone number of websites other then https://indoorsunhydro.com .',
            ],

            [
                'option_name' => 'ai_temprature',
                'type' => 'text',
                'option_value' => '1',
            ],

            [
                'option_name' => 'ai_top_p',
                'type' => 'text',
                'option_value' => '0.9',
            ],

            [
                'option_name' => 'ai_max_tokens',
                'type' => 'text',
                'option_value' => '4096',
            ],


            [
                'option_name'  => 'master_key_attempt',
                'type'         => 'boolean',
                'option_value' => 1,
            ],

            [
                'option_name'  => '500_error_message',
                'type'         => 'text',
                'option_value' => 'Oops! Something went wrong on our end.  We are working to fix the issue as soon as possible. In the meantime, you can try the following options:',
            ],
            [
                'option_name'  => 'surcharge_value_greater_weight',
                'type'         => 'number',
                'option_value' => '10',
            ],

            [
                'option_name'  => 'surcharge_type_greater_weight',
                'type'         => 'text',
                'option_value' => 'percentage',
            ],

            [
                'option_name' => 'apply_extra_surcharge',
                'type' => 'yes/no',
                'option_value' => 'No',
            ],

            [
                'option_name' => 'extra_charges_for_total_over_499',
                'type' => 'text',
                'option_value' => '6',
            ],

            [
                'option_name' => 'naris_indoor_email',
                'type' => 'text',
                'option_value' => 'naris@indoorsunhydro.com',
            ],
            [
                'option_name' => 'wally_shipstation_email',
                'type' => 'text',
                'option_value' => 'wally@indoorsunhydro.com',
            ],

            [
                'option_name' => 'wholesale_invoice_email_1',
                'type' => 'text',
                'option_value' => 'Vanessa@indoorsunhydro.com',
            ],

            [
                'option_name' => 'wholesale_invoice_email_2',
                'type' => 'text',
                'option_value' => 'mariana@indoorsunhydro.com',
            ],

            [
                'option_name' => 'wholesale_invoice_email_3',
                'type' => 'text',
                'option_value' => 'naris@indoorsunhydro.com',
            ],

            [
                'option_name' => 'auto_create_label',
                'type' => 'yes/no',
                'option_value' => 'No',
            ],

            [
                'option_name' => 'stock_tooltip_message',
                'type' => 'text',
                'option_value' => 'Inventory will arrive shortly, or internal teams will secure item for you to complete order in full.',
            ],

            [
                'option_name' => 'enable_wholesale_stripe_checkout',
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
