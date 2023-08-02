<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [
            [
                'site_name' => 'INDOOR SUN HYDRO',
                'site_url' => 'https://indoorsunhydro.com/',
            ],
            [ 
                'site_name' => 'LA GARDEN SUPPLY',
                'site_url' => 'https://qstage.lagardensupply.com',
            ],
        ];


        foreach($sites as $site) {
            $site_db = Site::firstOrCreate(
                [
                    'site_name' => $site['site_name'],
                    'site_url' => $site['site_url']
                ],
                [
                    'site_name' => $site['site_name'], 
                    'site_url' => $site['site_url']
                ]
            );
        }
    }
}
