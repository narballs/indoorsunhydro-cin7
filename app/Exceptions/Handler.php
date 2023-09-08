<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Notifications\SlackErrorNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    use Notifiable;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if(Auth::User()) {
                $errorLog = [
                    'Email' => auth()->user()->email,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code'=> $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ];
            }else {
                $errorLog = [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code'=> $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ];
            }
            //Log::channel('slack')->error($e->getMessage(), $errorLog);
        });
    }

    // public function report(Throwable $exception)
    // {
    //     if ($this->shouldReport($exception)) {
    //         $this->notify(new SlackErrorNotification($exception));
    //     }

    //     parent::report($exception);
    // }
}
