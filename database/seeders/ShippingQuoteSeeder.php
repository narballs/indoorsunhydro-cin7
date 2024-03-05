<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingQuote;
use App\Models\SelectedShippingQuote;

class ShippingQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shipping_quotes = [
            [
                'carrier_code' => 'ups_walleted',
                'service_code' => 'ups_ground',
                'carrier_name' => 'UPS by ShipStation',
                'service_name' => 'UPS® Ground',
                'type' => 'package',
            ],

            [
                'carrier_code' => 'ups_walleted',
                'service_code' => 'ups_3_day_select',
                'carrier_name' => 'UPS by ShipStation',
                'service_name' => 'UPS 3 Day Select®',
                'type' => 'package',
            ],
            
        ];


        foreach($shipping_quotes as $shipping_quote) {
            $shipping_quote = ShippingQuote::firstOrCreate(
                [
                    'carrier_code' => $shipping_quote['carrier_code'],
                    'service_code' => $shipping_quote['service_code'],
                    'carrier_name' => $shipping_quote['carrier_name'],
                    'service_name' => $shipping_quote['service_name'],
                    'type' => $shipping_quote['type'],
                ],
                [
                    'carrier_code' => $shipping_quote['carrier_code'],
                    'service_code' => $shipping_quote['service_code'],
                    'carrier_name' => $shipping_quote['carrier_name'],
                    'service_name' => $shipping_quote['service_name'],
                    'type' => $shipping_quote['type'],
                ]
            );
        }

        $selected_shipping_quotes = [
            [
                'shipping_quote_id' => 1,
            ],
            [
                'shipping_quote_id' => 2,
            ]
        ];
        foreach($selected_shipping_quotes as $selected_shipping_quotes) {
            $shipping_quote = SelectedShippingQuote::firstOrCreate(
                [
                    'shipping_quote_id' => $selected_shipping_quotes['shipping_quote_id'],
                ],
                [
                    'shipping_quote_id' => $selected_shipping_quotes['shipping_quote_id'],
                ]
            );
        }

    }
}
