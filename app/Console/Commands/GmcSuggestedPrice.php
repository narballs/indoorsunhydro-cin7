<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\GmcLog;
use App\Models\Pricingnew;
use App\Models\Product;
use App\Models\ProductOption;
use Google\Client as GoogleClient;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product as ServiceProduct;
use Google\Service\ShoppingContent\Price;

class GmcSuggestedPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        // Initialize Google Client
        $client = new GoogleClient();
        $client->setAuthConfig('master_credentials.json');
        $client->setScopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/content', // Add other necessary scopes
        ]);


        $token = $client->fetchAccessTokenWithAssertion();
        if (isset($token['access_token'])) {
            $result = $this->retriveProducts($client, $token);
            $gmcLog = GmcLog::orderBy('created_at', 'desc')->first();
            if (!empty($gmcLog)) {
                $gmcLog->last_updated_at = now();
                $gmcLog->save();
            } else {
                $create_gmc_log = new GmcLog();
                $create_gmc_log->last_updated_at = now();
                $create_gmc_log->save();
            }

            return $this->info('Products prices inserted successfully.'); 
        } else {
            $this->error('Failed to retrieve access token.');
        }
    }

    public function retriveProducts($client, $token) {
        $merchantId =config('services.google.merchant_center_id');
        $service = new ShoppingContent($client);
        $parameters = [];
        do {
            $products = $service->products->listProducts($merchantId, $parameters);
            
            if (!empty($products->getResources())) {
                // foreach ($products->getResources() as $product) {
                //     $productId = $product->getId();
                //     $title = $product->getTitle();
                //     $suggestedPrice = $product->getPrice()->getValue();
                    
                //     printf("%s %s - Suggested Price: %s\n", $productId, $title, $suggestedPrice);
                    
                //     // Update suggested price in database
                //     $stmt = $dbConnection->prepare("UPDATE products SET suggested_price = ? WHERE product_id = ?");
                //     $stmt->execute([$suggestedPrice, $productId]);
                // }
            }
            
            $parameters['pageToken'] = $products->getNextPageToken();
        } while (!empty($parameters['pageToken']));
    }
}
