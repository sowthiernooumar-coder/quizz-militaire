<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\RedirectResponse;

trait RedirectsToDashboard
{
    protected function redirectToDashboard(string $status, string $message): RedirectResponse
    {
        $user = auth()->user();

        $route = match (true) {
            $user->hasRole('admin')          => 'admin.dashboard',
            $user->hasRole('instructor_l2')  => 'instructor.dashboard',
            $user->hasRole('instructor_l1')  => 'instructor.dashboard',
            default                          => 'student.dashboard',
        };

        return redirect()->route($route)->with($status, $message);
    }
}
