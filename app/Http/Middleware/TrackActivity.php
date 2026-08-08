<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;

class TrackActivity
{
    public function handle(
        $request,
        Closure $next
    )
    {
        $response =
            $next($request);

        if(auth()->check())
        {
            ActivityLog::create([

                'user_id' =>
                    auth()->id(),

                'action' =>
                    $request->method(),

                'description' =>
                    $request->path()
            ]);
        }

        return $response;
    }
}