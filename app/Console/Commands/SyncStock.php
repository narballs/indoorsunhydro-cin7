<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductOption;
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
        $total_products_pages = 150;

        $total_stock = 0;

        $product_array = [];

        for ($i = 1; $i <= $total_products_pages; $i++) {
            sleep(1);

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
            
            foreach ($api_stock as $stock) {

                if (isset($product_array[$stock->productId])) {
                    $product_array[$stock->productId]['quantity'] = $product_array[$stock->productId]['quantity'] + $stock->available;
                }
                else {
                    $product_array[$stock->productId] = [
                        'quantity' => $stock->available
                    ];
                }
            
            }

            if (!empty($product_array)) {
                foreach ($product_array as $product_id => $stock_quantity) {
                    $product_option = ProductOption::where('product_id', $product_id)->first();

                    if (!empty($product_option) && $product_option->stockAvailable != $stock_quantity['quantity'])
                    {
                        $product_option->stockAvailable = $stock_quantity['quantity'];
                        $product_option->stockOnHand = $stock_quantity['quantity'];
                        $product_option->save();
                    }

                }
            }
        }
    }
}
