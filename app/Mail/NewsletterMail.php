<?php

namespace App\Mail;

use App\Models\NewsletterTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;

    public function __construct(NewsletterTemplate $template)
    {
        $this->template = $template;
    }

    public function build()
    {
        return $this->subject($this->template->name)->view('emails.newsletter_template', ['template' => $this->template]);
    }
}
