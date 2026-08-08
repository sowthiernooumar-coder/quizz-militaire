<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginLog;

class LoginLogController extends Controller
{
    public function index()
    {
        return LoginLog::with(
            'user'
        )

        ->latest()

        ->paginate(50);
    }
}