<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\UserActivity;

class TrackUserActivity
{
    public function handle(
        $request,
        Closure $next
    )
    {
        if (auth()->check()) {

            UserActivity::updateOrCreate(

                [
                    'user_id' =>
                        auth()->id()
                ],

                [
                    'last_activity_at' =>
                        now()
                ]
            );
        }

        return $next($request);
    }
}