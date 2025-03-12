<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GoogleAdsData;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;
use Exception;

class FetchGoogleAdsData extends Command
{
    protected $signature = 'googleads:fetch';
    protected $description = 'Fetch daily Google Ads data and store it in the database';

    public function handle()
    {
        try {
            // Initialize Google API Client
            $client = new GoogleClient();
            $client->setAuthConfig(base_path('master_credentials.json'));
            $client->setScopes([
                'https://www.googleapis.com/auth/adwords',
            ]);

            // Fetch Access Token
            $token = $client->fetchAccessTokenWithAssertion();

            if (!isset($token['access_token'])) {
                Log::error("Google Ads API: Failed to get access token.");
                $this->error("Failed to get access token.");
                return;
            }

            $access_token = $token['access_token'];
            Log::info("Google Ads API: Access token retrieved successfully.");

            // Fetch Google Ads Data
            $data = $this->fetchGoogleAdsData($access_token);

            // Log full response for debugging
            Log::info("Google Ads API Response: " . json_encode($data, JSON_PRETTY_PRINT));

            // Handle no data case
            if (!isset($data['results']) || empty($data['results'])) {
                $this->error("No data returned from Google Ads API.");
                Log::warning("Google Ads API: No data returned.");
                return;
            }

            // Process and store data
            foreach ($data['results'] as $row) {
                $id = $row['campaign']['id'];
                $clicks = $row['metrics']['clicks'];
                $impressions = $row['metrics']['impressions'];
                $spend = $row['metrics']['cost_micros'] / 1e6; // Convert micros to currency
                $date = $row['segments']['date'];

                GoogleAdsData::updateOrCreate(
                    ['google_ads_id' => $id],
                    ['clicks' => $clicks, 'impressions' => $impressions, 'spend' => $spend, 'date' => $date]
                );
            }

            $this->info("Google Ads data successfully fetched and stored.");
            Log::info("Google Ads API: Data successfully stored.");

        } catch (Exception $e) {
            Log::error("Error in FetchGoogleAdsData: " . $e->getMessage());
            $this->error("Error: " . $e->getMessage());
        }
    }

    /**
     * Fetches Google Ads data using API
     */
    public function fetchGoogleAdsData($access_token)
    {
        try {
            $customer_id = config('services.google.customer_id');
            $developer_token = config('services.google.developer_token');

            if (!$access_token) {
                throw new Exception("Access token is missing.");
            }

            if (!$customer_id || !$developer_token) {
                throw new Exception("Google Ads Customer ID or Developer Token is missing.");
            }

            $url = "https://googleads.googleapis.com/v15/customers/{$customer_id}/googleAds:search";

            $query = [
                'query' => "
                    SELECT
                        campaign.id,
                        campaign.name,
                        metrics.clicks,
                        metrics.impressions,
                        metrics.cost_micros,
                        segments.date
                    FROM campaign
                    WHERE segments.date DURING LAST_30_DAYS
                "
            ];

            $ch = curl_init();
            if ($ch === false) {
                throw new Exception("Failed to initialize cURL.");
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $access_token",
                "developer-token: $developer_token",
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new Exception("cURL error: " . curl_error($ch));
            }

            curl_close($ch);

            // Log HTTP response code
            Log::info("Google Ads API HTTP Response Code: " . $http_code);

            // Check for API errors
            if ($http_code !== 200) {
                Log::error("Google Ads API Error: HTTP Code $http_code, Response: $response");
                throw new Exception("Google Ads API Error: HTTP Code $http_code");
            }

            return json_decode($response, true);

        } catch (Exception $e) {
            Log::error("Error in fetchGoogleAdsData: " . $e->getMessage());
            $this->error("Error: " . $e->getMessage());
            return [];
        }
    }
}
