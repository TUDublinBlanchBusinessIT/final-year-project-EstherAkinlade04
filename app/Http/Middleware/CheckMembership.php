<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->end_date) {

            if (Carbon::parse($user->end_date)->isPast()) {

                return redirect()->route('dashboard')
                    ->with('error', 'Your membership has expired. Please renew.');
            }

        }

        return $next($request);
    }
}