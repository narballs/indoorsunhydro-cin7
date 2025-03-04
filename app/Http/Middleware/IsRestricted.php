<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class IsRestricted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (auth()->user()) {
            $user = User::find(auth()->id());
            if ($user->hasRole(['Newsletter']) || $user->hasRole(['Sale Payments']) || $user->hasRole(['Payouts'])) {
                return redirect()->route('newsletter_dashboard');
            } else {
                return $next($request);
            }
        }

        return $next($request);
    }
}
