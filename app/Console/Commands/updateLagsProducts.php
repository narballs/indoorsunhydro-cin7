<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Product;
class UpdateLagsProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lags-products';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Lags Products';
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
        $client = new Client(); // Instantiate Guzzle client outside the loop
        $indoor_products = Product::with('options')->get();
        if (count($indoor_products) > 0) {
            foreach ($indoor_products as $indoor_product) {
                if (count($indoor_product['options']) > 0) {
                    foreach($indoor_product['options'] as $option) {
                    // Assuming $api_product is defined somewhere in your code
                        $api_product = [
                            'id' => $indoor_product->id,
                            'name' => $indoor_product->name,
                            'code' => $indoor_product->code,
                            'description' => !empty($indoor_product->description) ? $indoor_product->description : '',
                            'optionWeight' => $option->optionWeight,
                            'images' => $indoor_product->images,
                            'option1' => $option->option1,
                            'option2' => $option->option2,
                            'option3' => $option->option3,
                            'size' => $option->size,
                        ];
                        $url = 'https://qstage.lagardensupply.com/api/product-webhook';
                        $response = $client->post($url, [
                            'form_params' => $api_product
                        ]);
                    }
                }
            }
        }
    }
}












