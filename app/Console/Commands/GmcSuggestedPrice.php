<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client as GoogleClient;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\SearchRequest;
use App\Models\GmcLog;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Pricingnew;

class GmcSuggestedPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ai_suggested_prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync AI suggested prices with Google Merchant Center';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Initialize Google Client
        $client = new GoogleClient();
        $client->setAuthConfig('master_credentials.json');
        $client->setScopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/content',
        ]);

        // Fetch access token
        $token = $client->fetchAccessTokenWithAssertion();

        if (isset($token['access_token'])) {
            // ✅ Fetch products
            $result = $this->retrieveProducts($client, $token);

            if (!is_array($result)) {
                $this->error('Failed to retrieve products: ' . $result);
                return;
            }

            // ✅ Add suggested prices
            $this->addSuggestedPrices($result);

            // ✅ Update or create a GMC log entry
            $gmcLog = GmcLog::latest()->first();
            if ($gmcLog) {
                $gmcLog->last_updated_at = now();
                $gmcLog->save();
            } else {
                GmcLog::create(['last_updated_at' => now()]);
            }

            return $this->info('Products prices inserted successfully.'); 
        } else {
            $this->error('Failed to retrieve access token.');
        }
    }

    private function retrieveProducts($client, $token)
    {
        try {
            $merchantId = config('services.google.merchant_center_id');

            // Set the access token
            $client->setAccessToken($token['access_token']);

            // Initialize Google Shopping Content API
            $service = new ShoppingContent($client);

            // ✅ Corrected SQL query
            $query = "SELECT
                        product_view.id, 
                        product_view.title, 
                        product_view.brand,
                        product_view.price_micros,
                        price_insights.suggested_price_micros
                    FROM PriceInsightsProductView";

            // Create SearchRequest object
            $searchRequest = new SearchRequest();
            $searchRequest->setQuery($query);

            // Make API request
            $response = $service->reports->search($merchantId, $searchRequest);
            $finalResults = [];

            if (!empty($response->results)) {
                foreach ($response->results as $product) {
                    // Extract product details safely
                    $productView = $product->productView ?? null;
                    $priceInsights = $product->priceInsights ?? null;

                    $finalResults[] = [
                        'title' => $productView->title ?? 'N/A',
                        'brand' => $productView->brand ?? 'N/A',
                        'price' => isset($productView->priceMicros) ? number_format($productView->priceMicros / 1000000, 2) : '0.00',
                        'suggested_price' => isset($priceInsights->suggestedPriceMicros) ? number_format($priceInsights->suggestedPriceMicros / 1000000, 2) : '0.00'
                    ];
                }
            }

            return $finalResults;

        } catch (\Exception $e) {
            // return $e->getMessage();
            return ;
        }
    }

    private function addSuggestedPrices($products)
    {
        foreach ($products as $productData) {
            $product = Product::where('name', $productData['title'])->first();
            
            if (!$product) {
                continue; // ✅ Skip if product not found
            }

            $productOption = ProductOption::where('product_id', $product->product_id)->first();
            if (!$productOption) {
                continue; // ✅ Skip if no product option found
            }

            $pricing = Pricingnew::where('option_id', $productOption->option_id)->first();
            if ($pricing) {
                $pricing->aiPriceUSD = $productData['suggested_price'];
                $pricing->enable_ai_price = 1;
                $pricing->save();
            }
        }
    }
}
