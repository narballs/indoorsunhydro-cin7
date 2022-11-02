<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::truncate();

        // $statuses = ['New','Awaiting Paymet','Procressing', 'Release to Pick', 'Partially Picked', 'Fully Picked', 'Fully Picked-Hold', 'On-Hold', 'Dispatched', 'Cancelled'];
        //dd($statuses);exit;
        $statuses =  ['DRAFT','APPROVED','VOID'];
        foreach($statuses as $status) {
            OrderStatus::create([
                'status' => $status
            ]);
        }

    }
}
