<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Throwable;

class SlackErrorNotification extends Notification
{
    use Queueable;

    protected $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $user = auth()->check() ? auth()->user()->email : 'Guest';
        $env  = app()->environment();
        $url  = url()->current();

        return (new SlackMessage)
            ->error()
            ->content(":rotating_light: *Exception in " . strtoupper($env) . " environment!*")
            ->attachment(function ($attachment) use ($user, $url, $env) {
                $fields = [
                    'Exception' => get_class($this->exception),
                    'Message'   => $this->exception->getMessage(),
                    'File'      => $this->exception->getFile(),
                    'Line'      => $this->exception->getLine(),
                    'URL'       => "<{$url}|Open URL>",
                    'User'      => $user,
                ];

                // Only include stack trace in production
                if ($env === 'production') {
                    $fields['Trace'] = "```" . substr($this->exception->getTraceAsString(), 0, 2000) . "```";
                }

                $attachment
                    ->title('Exception Details')
                    ->fields($fields);
            });
    }
}
