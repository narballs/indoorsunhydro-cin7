<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GoogleAdsData;
use Carbon\Carbon;
use Exception;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;

class FetchGoogleAdsData extends Command
{
    protected $signature = 'googleads:fetch';
    protected $description = 'Fetch daily Google Ads data and store it in the database';

    public function handle()
    {
        try {
            $client = new GoogleClient();

            // Set authentication credentials
            $client->setAuthConfig('master_credentials.json');

            // Set the required scopes
            $client->setScopes([
                'openid',
                'profile',
                'email',
                'https://www.googleapis.com/auth/adwords', // Use Google Ads API scope
            ]);

            // Fetch the access token with the service account's credentials
            $token = $client->fetchAccessTokenWithAssertion();

            if (isset($token['error'])) {
                Log::error("Error in FetchGoogleAdsData: " . $token['error']);
            }

            // Check if the token contains an access_token
            if (!isset($token['access_token'])) {
                Log::error("Error in FetchGoogleAdsData: Access token not found.");
            }

            // If no refresh token, use the access token only (service accounts may not have refresh tokens)
            $access_token = $token['access_token'];
            $refresh_token = isset($token['refresh_token']) ? $token['refresh_token'] : null;

            // Fetch Google Ads data
            $data = $this->fetchGoogleAdsData($access_token);

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

    public function fetchGoogleAdsData($access_token)
    {
        try {
            $customer_id = config('services.google.customer_id');
            $developer_token = config('services.google.developer_token');

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
                Log::error("cURL Error: " . $error_message);
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
}
