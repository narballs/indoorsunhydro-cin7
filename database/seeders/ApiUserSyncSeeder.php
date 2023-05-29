<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiSyncLog;
use Carbon\Carbon;

class ApiUserSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$date = '2023-29-31 00:00:00';
        $dt = Carbon::now();
        // $dt->year   = 2023;
        // $dt->month  = 05;
        // $dt->day    = 29;
        // $dt->hour   = 00;
        // $dt->minute = 00;
        // $dt->second = 00;
        $formattedDateSting = $dt->toDateTimeString();
        
        $sync_logs =  
            [
                [
                    'end_point' => 'https://api.cin7.com/api/v1/Contacts', 
                    'desription' => 'Contacts Sync', 
                    'last_synced' => $formattedDateSting
                ],
                [
                    'end_point' => 'https://api.cin7.com/api/v1/Products', 
                    'desription' => 'Products Sync', 
                    'last_synced' => $formattedDateSting
                ],
       
            ];
        foreach($sync_logs as $sync_log) {
            APiSyncLog::create($sync_log);
        }

    }
}
