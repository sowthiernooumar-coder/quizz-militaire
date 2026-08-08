<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\ActivityLog;

class LogController extends Controller
{
    public function index()
    {
        $logins = LoginLog::with(
            'user'
        )

        ->latest()

        ->paginate(20);

        $activities =
            ActivityLog::with(
                'user'
            )

            ->latest()

            ->paginate(20);

        return view(
            'admin.logs.index',
            compact(
                'logins',
                'activities'
            )
        );
    }
}