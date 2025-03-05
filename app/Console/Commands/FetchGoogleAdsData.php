<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Ads\GoogleAds\GoogleAdsClient;
use Google\Ads\GoogleAds\GoogleAdsClientBuilder;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\ApiCore\ApiException;
use App\Models\GoogleAdsData;
use Carbon\Carbon;
use Google\Ads\GoogleAds\V8\Services\SearchGoogleAdsRequest;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder as V8GoogleAdsClientBuilder;

class FetchGoogleAdsData extends Command {
    protected $signature = 'googleads:fetch';
    protected $description = 'Fetch daily Google Ads data and store it in the database';

    public function handle() {
        try {
            // Load environment variables
            $customer_id = config('services.google.customer_id');
            $developer_token = config('services.google.developer_token');
            $manager_id = config('services.google.manager_id');
            $keyFilePath = base_path('master_credentials.json'); // Ensure file exists

            // Validate credentials file
            if (!file_exists($keyFilePath)) {
                $this->error("Service account JSON file not found: {$keyFilePath}");
                return;
            }

            // Load Google Ads Service Account Credentials
            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/adwords'],
                json_decode(file_get_contents($keyFilePath), true)
            );

            // ✅ Correctly Initialize Google Ads API Client
            $client = (new V8GoogleAdsClientBuilder())
                ->withDeveloperToken($developer_token)
                ->withOAuth2Credential($credentials)
                ->withLoginCustomerId($manager_id) // Use MCC ID if managing multiple accounts
                ->build();

            $googleAdsServiceClient = $client->getGoogleAdsServiceClient();

            // ✅ Define Google Ads Query
            $query = "
                SELECT 
                    segments.date, 
                    metrics.clicks, 
                    metrics.impressions, 
                    metrics.cost_micros,
                    campaign.id
                FROM campaign
            ";

            // ✅ Fix: Use `search()` correctly (pass customer_id and query string)
            $response = $googleAdsServiceClient->search($customer_id, $query);

            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $date = Carbon::parse($googleAdsRow->getSegments()->getDate());
                $clicks = $googleAdsRow->getMetrics()->getClicks();
                $impressions = $googleAdsRow->getMetrics()->getImpressions();
                $spend = $googleAdsRow->getMetrics()->getCostMicros() / 1000000; // Convert micros to actual currency
                $id = $googleAdsRow->getCampaign()->getId(); // Get Campaign ID

                // ✅ Store data in the database correctly
                GoogleAdsData::updateOrCreate(
                    ['google_ads_id' => $id],
                    ['clicks' => $clicks, 'impressions' => $impressions, 'spend' => $spend, 'date' => $date]
                );
            }

            $this->info('Google Ads data updated successfully.');

        } catch (ApiException $e) {
            $this->error('Google Ads API Error: ' . $e->getMessage());
        }
    }
}
