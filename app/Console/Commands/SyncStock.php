<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
class SyncStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $signature = 'Sync:Stock';

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
       
        $client2 = new \GuzzleHttp\Client();
        $products = Product::all()->pluck('product_id', 'stockAvailable');
        $total_products_pages = 250;


        for ($i = 1; $i <= $total_products_pages; $i++) {
            $this->info('Processing page#' . $i);
            sleep(2);

            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Stock?page=' . $i, 
                [
                    'auth' => [
                        'IndoorSunHydroUS', 
                        'faada8a7a5ef4f90abaabb63e078b5c1'
                    ]
                     
                ]
            );
            $api_stock = $res->getBody()->getContents();
            $api_stock = json_decode($api_stock);
            $total_stock = 0;
            //$product = Product::where('product_id',$api_stock[0]->productId)->first();
            $product = Product::find($api_stock[0]->productId);
            foreach($api_stock as $stock) {
                //dd($stock);
                $this->info('--------------------');
                $this->info($stock->available);
                $total_stock_1 = $total_stock + $stock->available;
                $available_stock = intval($total_stock_1);
            }
            if (!empty($product)) { 
                $product->stockAvailable = $available_stock;
                $product->save();
            }
        }
    }

}
