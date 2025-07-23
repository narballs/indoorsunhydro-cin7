<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Notifications\SlackErrorNotification;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        // Only notify Slack if the environment is 'production'
        if (
            app()->environment('production') &&
            $this->shouldReport($exception)
        ) {
            (new SlackNotifier)->notify(new SlackErrorNotification($exception));
        }
        parent::report($exception);
    }
}

/**
 * Dummy notifiable for Slack notifications.
 */
class SlackNotifier
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForSlack($notification = null)
    {
        return config('logging.channels.slack.url');
    }
}
