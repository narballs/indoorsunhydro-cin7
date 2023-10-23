<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiOrder;
use Illuminate\Support\Facades\DB;

class OrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:OrderStatuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = ApiOrder::all();
        $order_chunks = $orders->chunk(100);
        $order_chunks_to_array = $order_chunks->toArray();
        foreach ($order_chunks_to_array as $order_chunk_get) {
            foreach ($order_chunk_get as $order) {
                if (empty($order->order_status_id) || $order->order_status_id == null) {
                    $update_order = ApiOrder::find($order['id']);
                    if ($order['isApproved'] == 1 && $order['isVoid'] == 1) {
                        $order_status_void = DB::table('order_status')->where(DB::raw("LOWER(status)"), strtolower('Void'))->first();
                        if (!empty($order_status_void)) {
                            $update_order->update([
                                'order_status_id' => $order_status_void->id
                            ]);
                        }
                    } elseif ($order['isApproved'] == 0 && $order['isVoid'] == 0 ) {
                        $order_status_new = DB::table('order_status')->where(DB::raw("LOWER(status)"), strtolower('New'))->first();
                        if (!empty($order_status_new)) {
                            $update_order->update([
                                'order_status_id' => $order_status_new->id
                            ]);
                        }
                    } elseif ($order['isApproved'] == 1) {
                        $order_status_fullfilled = DB::table('order_status')->where(DB::raw("LOWER(status)"), strtolower('FullFilled'))->first();
                        if (!empty($order_status_fullfilled)) {
                            $update_order->update([
                                'order_status_id' => $order_status_fullfilled->id
                            ]);
                        }
                    } elseif ($order['isApproved'] == 2) {
                        $order_status_cancelled = DB::table('order_status')->where(DB::raw("LOWER(status)"), strtolower('Cancelled'))->first();
                        if (!empty($order_status_cancelled)) {
                            $update_order->update([
                                'order_status_id' => $order_status_cancelled->id
                            ]);
                        }
                    }
                }
            }
        }
    }

}
