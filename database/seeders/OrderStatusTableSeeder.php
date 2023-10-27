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
        $statuses =  [
            ['status' => 'Pending payment'],
            ['status' => 'Preparing for Shipping' ],
            ['status' => 'On hold' ],
            ['status' => 'Order Completed' ],
            ['status' => 'Cancelled'],
            ['status' => 'Refunded'],
            ['status' => 'DRAFT'],
            ['status' => 'APPROVED'],
            ['status' => 'VOID'],
            ['status' => 'New'],
            ['status' => 'FullFilled'],
            ['status' => 'Paid - Preparing for Shipping']
        ];
        foreach($statuses as $status) {

            $order_status = OrderStatus::firstOrCreate(
                [
                    'status' => $status['status']
                ]
            );
        }

    }
}
