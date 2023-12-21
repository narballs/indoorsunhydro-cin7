<?php

namespace App\Services;

use Google_Client;

class GoogleContentApiService
{
    protected $client;

    public function __construct()
    {
        $credentialsPath = base_path('main_credentials.json');
        $this->client = new Google_Client();
        $this->client->setAuthConfig($credentialsPath);
        $this->client->setScopes(['https://www.googleapis.com/auth/content']);

        // Other configuration options as needed
    }

    public function getClient()
    {
        return $this->client;
    }
}
