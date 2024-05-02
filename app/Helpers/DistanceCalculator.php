<?php

namespace App\Helpers;

use GoogleMaps\Geocoding\GeocodingClient;
use GoogleMaps\DistanceMatrix\DistanceMatrixService;

class DistanceCalculator
{
    public function calculate_distance($zip1, $zip2)
    {
        
        // API key for Google Maps API (you need to obtain one from Google)
        $apiKey = config('services.google_address_validator.address_validator_google_key');

        // Create a Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Send a request to Google Maps Distance Matrix API
        $response = $client->get("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=$zip1&destinations=$zip2&key=$apiKey");

        // Decode the JSON response
        $data = json_decode($response->getBody(), true);
        
        $distance = 'Error';
        if ($data['status'] !== 'OK') {
            return $distance;
        } else {
            $response_data = $data['rows'][0]['elements'][0]['distance']['value'];
            $calculate_in_kms_distance = $response_data / 1000;
            $calculate_in_miles_distance = round($calculate_in_kms_distance * 0.621371, 2);
            return $calculate_in_miles_distance;
        }
    }
}