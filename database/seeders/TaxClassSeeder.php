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
                'name' => 'CA-Scarmento',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Scarmento-Alameda',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Scarmento-Del Norte',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Scarmento-Scarmento',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Scarmento-Scarmento-Scarmento',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Scarmento-Scarmento-San Jaquin-Stockon',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Scarmento-Santa Clara',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Farm Exempt-Clerveras',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Farm Exempt-OK',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Farm Exemption',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98281',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Clear',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Alameda-San Leandro',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98382',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98355',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Yuba',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Yreka',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-San Francisco',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Sacramento-Rancho Cordova',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Placerville',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Nevada',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Merced-Merced',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Los Angeles-Vernon',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Los Angeles',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Humboldt-Arcata',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'CA-Sacramento-Humboldt',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-98363',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'Out of scope',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Tulsa-Tulsa',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Seminole',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Mcclain',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Craig',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Cleveland-Oklahoma',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OK-Cleveland-Norman',
                'rate' => '0.00',
                'is_default' => 0
            ],
            [
                'name' => 'OKC',
                'rate' => '0.00',
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
            ]
        ];
        foreach($tax_classes as $tax_class) {
             TaxClass::create($tax_class);
        }
    }
}
