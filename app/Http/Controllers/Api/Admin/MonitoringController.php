<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Models\UserActivity;

class MonitoringController extends Controller
{
    //En ligne Students
    public function onlineStudents()
    {
        return UserActivity::whereHas(

            'user.roles',

            function($query){

                $query->where(
                    'name',
                    'student'
                );
            }
        )

        ->where(
            'last_activity_at',
            '>=',
            now()->subMinutes(5)
        )

        ->with('user')

        ->get();
    } 

    //En ligne L1 Instructor
    public function onlineL1()
    {
        return UserActivity::whereHas(

            'user.roles',

            function($query){

                $query->where(
                    'name',
                    'instructor_l1'
                );
            }
        )

        ->where(
            'last_activity_at',
            '>=',
            now()->subMinutes(5)
        )

        ->with('user')

        ->get();
    }
}