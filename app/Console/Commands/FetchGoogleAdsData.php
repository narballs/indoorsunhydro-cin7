<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GoogleAdsData;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class FetchGoogleAdsData extends Command {
    protected $signature = 'googleads:fetch';
    protected $description = 'Fetch daily Google Ads data and store it in the database';

    public function handle()
    {
        try {
            // Fetch Google Ads data
            $data = $this->fetchGoogleAdsData();

            if (!isset($data['results'])) {
                $this->error("No data returned from Google Ads API.");
                return;
            }

            foreach ($data['results'] as $row) {
                $id = $row['campaign']['id'];
                $clicks = $row['metrics']['clicks'];
                $impressions = $row['metrics']['impressions'];
                $spend = $row['metrics']['costMicros'] / 1e6; // Convert micros to currency
                $date = $row['segments']['date'];

                // Store or update data in the database
                GoogleAdsData::updateOrCreate(
                    ['google_ads_id' => $id],
                    ['clicks' => $clicks, 'impressions' => $impressions, 'spend' => $spend, 'date' => $date]
                );
            }

            $this->info("Google Ads data successfully fetched and stored.");

        } catch (Exception $e) {
            // Catch any exceptions and log the error
            $this->error("Error: " . $e->getMessage());
            // Optionally log the error details
            Log::error("Error in FetchGoogleAdsData: " . $e->getMessage());
        }
    }

    public function fetchGoogleAdsData()
    {
        try {
            $customer_id = config('services.google.customer_id');
            $developer_token = config('services.google.developer_token');
            $access_token = $this->getAccessToken();

            if (!$access_token) {
                throw new Exception("Failed to get access token.");
            }

            $url = "https://googleads.googleapis.com/v12/customers/{$customer_id}/googleAds:search";

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

            // Initialize cURL session
            $ch = curl_init();
            if ($ch === false) {
                throw new Exception("Failed to initialize cURL session.");
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

            // Execute cURL request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                throw new Exception("cURL Error: $error_message");
            }

            // Close cURL handle
            curl_close($ch);

            $result = json_decode($response, true);

            return $result;

        } catch (Exception $e) {
            // Catch any exceptions during API call
            $this->error("Error: " . $e->getMessage());
            // Optionally log the error details
            Log::error("Error in fetchGoogleAdsData: " . $e->getMessage());
            return [];
        }
    }

    public function getAccessToken()
    {
        try {
            $client_id = config('services.google.client_id');
            $client_secret = config('services.google.client_secret');
            $refresh_token = config('services.google.refresh_token'); // Stored in .env or the config

            $url = "https://oauth2.googleapis.com/token";

            $data = [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'refresh_token' => $refresh_token,
                'grant_type' => 'refresh_token'
            ];

            // Initialize cURL session
            $ch = curl_init();
            if ($ch === false) {
                throw new Exception("Failed to initialize cURL session for access token.");
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL request for access token
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                $error_message = curl_error($ch);
                curl_close($ch);
                Log::error("cURL Error: $error_message");
            }

            // Close cURL handle
            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['access_token'])) {
                return $result['access_token'];
            } else {
                Log::error("Error in getAccessToken: " . json_encode($result));
            }

        } catch (Exception $e) {
            // Catch any exceptions during access token retrieval
            $this->error("Error: " . $e->getMessage());
            // Optionally log the error details
            Log::error("Error in getAccessToken: " . $e->getMessage());
            return null;
        }
    }
}
