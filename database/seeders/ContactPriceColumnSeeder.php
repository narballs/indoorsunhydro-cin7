<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactPriceColumn;

class ContactPriceColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $contact_price_columns = [
            [
                'site_id' => 1,
                'price_column' => 'retailUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'wholesaleUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'terraInternUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'sacramentoUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'oklahomaUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'calaverasUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'tier1USD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'tier2USD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'tier3USD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'commercialOKUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'tier0USD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'costUSD',
            ],
            [ 
                'site_id' => 1,
                'price_column' => 'specialPrice',
            ],

            // Price Column for site - 2

            [ 
                'site_id' => 2,
                'price_column' => 'retailUSD'
            ],
            [ 
                'site_id' => 2,
                'price_column' => 'wholesaleUSD'
            ],
            [ 
                'site_id' => 2,
                'price_column' => 'disP1USD'
            ],
            [ 
                'site_id' => 2,
                'price_column' => 'disP2USD'
            ],
            [ 
                'site_id' => 2,
                'price_column' => 'comccusd'
            ],
            [ 
                'site_id' => 2,
                'price_column' => 'com1USD'
            ],

            [ 
                'site_id' => 2,
                'price_column' => 'msrpusd'
            ],

            [ 
                'site_id' => 2,
                'price_column' => 'costUSD'
            ],
            [ 
                'site_id' => 2,
                'price_column' => 'specialPrice'
            ]

        ];


        foreach($contact_price_columns as $contact_price_column) {

            $contact_price_column_db = ContactPriceColumn::firstOrCreate(
                [
                    'site_id' => $contact_price_column['site_id'],
                    'price_column' => $contact_price_column['price_column']
                ],
                [
                    'site_id' => $contact_price_column['site_id'], 
                    'price_column' => $contact_price_column['price_column']
                ]
            );
        }
    }
}
