<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxClass;

class TaxClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxClass::truncate();
        $tax_classes =
        [
            [
                'name' => 'none',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Exempt',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => '7.25%',
                'rate' => '7.25',
                'is_default' => 0
            ],
            [
                'name' => '8.75%',
                'rate' => '8.75%',
                'is_default' => 1
            ],
            [
                'name' => 'Sacramento',
                'rate' => '8.75%',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento',
                'rate' => '7.25',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Alameda',
                'rate' => '10.25',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Del Norte',
                'rate' => '8.25',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Sacramento',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Sacramento-Sacramento',
                'rate' => '2.50',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Sacramento-San Jaquin-Stockton',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Santa Clara',
                'rate' => '9.125',
                'is_default' => 0
            ],
            [
                'name' => 'Farm Exempt-Clerveras',
                'rate' => '2.25',
                'is_default' => 0
            ],
            [
                'name' => 'Farm Exempt-OK',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Farm Exemption',
                'rate' => '3.75',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98281',
                'rate' => '5.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Alameda-San Leandro',
                'rate' => '10.25',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98382',
                'rate' => '5.375',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98355',
                'rate' => '8.625',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Yuba',
                'rate' => '8.25',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Yreka',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-San Francisco',
                'rate' => '8.625',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Sacramento-Rancho Cordova',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Placerville',
                'rate' => '8.25',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Nevada',
                'rate' => '7.50',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Merced-Merced',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Los Angeles-Vernon',
                'rate' => '9.50',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Los Angeles',
                'rate' => '9.50',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Humboldt-Arcata',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Humboldt',
                'rate' => '7.75',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98363',
                'rate' => '8.625',
                'is_default' => 0
            ],
            [
                'name' => 'Out of scope',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Tulsa-Tulsa',
                'rate' => '8.517',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Seminole',
                'rate' => '5.75',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Mcclain',
                'rate' => '5.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Craig',
                'rate' => '6.50',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Cleveland-Oklahoma',
                'rate' => '8.75',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Cleveland-Norman',
                'rate' => '8.75',
                'is_default' => 0
            ],
            [
                'name' => 'OKC',
                'rate' => '8.63',
                'is_default' => 0
            ],
            [
                'name' => 'Out of State',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'POS Exempt Sales',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98804',
                'rate' => '5.50',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98816',
                'rate' => '5.00',
                'is_default' => 0
            ]
        ];
        foreach($tax_classes as $tax_class) {
             TaxClass::create($tax_class);
        }
    }
}
