<?php

namespace App\Services;

use Zendesk\API\HttpClient as ZendeskClient;

class ZendeskService
{
    protected $client;

    public function __construct()
    {
        $this->client = new ZendeskClient(
            env('ZENDESK_SUBDOMAIN'),
            env('ZENDESK_USERNAME'),
            env('ZENDESK_TOKEN')
        );
    }

    public function createTicket($data)
    {
        // Use the Zendesk client to create a ticket
        return $this->client->tickets()->create($data);
    }

    // You can define more methods to interact with Zendesk API as per your requirements
}