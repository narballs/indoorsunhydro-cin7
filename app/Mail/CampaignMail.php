<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;

    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    public function build()
    {
        $trackingUrl = route('track.open', ['campaign_id' => $this->campaign->id]);
        return $this->view('emails.campaign')
        ->with([
            'campaign' => $this->campaign,
            'trackingUrl' => $trackingUrl,
        ]);
    }
}