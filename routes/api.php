<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\PromotionController;
use App\Http\Controllers\Api\Admin\SubjectController;
use App\Http\Controllers\Api\InstructorL1\QuizController;
use App\Http\Controllers\Api\InstructorL1\QuestionController;
use App\Http\Controllers\Api\Student\QuizPlayController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\InstructorL2\InstructorManagementController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\Admin\MonitoringController;
use App\Http\Controllers\Api\NotificationController;

Route::post('/login',[AuthController::class,'login']);

Route::post('/register',[AuthController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class,'logout']);

});

//Route Admin
Route::prefix('admin')->middleware([
    'auth:sanctum',
    'role:admin'
])->group(function () {

    Route::apiResource(
        'users',
        UserController::class
    );

    Route::apiResource(
        'promotions',
        PromotionController::class
    );

    Route::apiResource(
        'subjects',
        SubjectController::class
    );

    Route::get(
        '/dashboard',
        [DashboardController::class,'index']
    );

    Route::post(
        '/users/{user}/activate',
        [UserController::class,'activate']
    );

    Route::post(
        '/users/{user}/deactivate',
        [UserController::class,'deactivate']
    );

    Route::post(
        '/users/{user}/reset-password',
        [UserController::class,'resetPassword']
    );

    Route::get(
        '/statistics/top-students',
        [StatisticsController::class,'topStudents']
    );

    Route::get(
        '/statistics/subjects',
        [StatisticsController::class,'subjectStatistics']
    );

    Route::get(
        '/statistics/promotions',
        [StatisticsController::class,'promotionStatistics']
    );

    Route::get(
        '/statistics/monthly-evolution',
        [StatisticsController::class,'monthlyEvolution']
    );

    Route::get(
        '/statistics/success-rate',
        [StatisticsController::class,'successRate']
    );

    Route::middleware('track.activity')->get(

        '/online-users',

        [
            MonitoringController::class,
            'onlineUsers'
        ]
    );

});

//Route L1|L2 Instructor
Route::middleware([
    'auth:sanctum',
    'role:instructor_l1|instructor_l2'
])->group(function () {

    Route::apiResource(
        'quizzes',
        QuizController::class
    );

    Route::apiResource(
        'questions',
        QuestionController::class
    );

});

//Route Student
Route::middleware([
    'auth:sanctum',
    'role:student'
])->prefix('student')->group(function () {

    Route::post(
        '/start-quiz',
        [QuizPlayController::class,'start']
    );

    Route::post(
        '/submit-quiz/{session}',
        [QuizPlayController::class,'submit']
    );

    Route::get(
        '/result/{session}',
        [QuizPlayController::class,'result']
    );

    Route::get(
        '/dashboard',
        [StudentDashboardController::class,'index']
    );
});

//Route L2 Instructor
Route::middleware([
    'auth:sanctum',
    'role:instructor_l2'
])

->prefix('l2')

->group(function () {

    Route::post(

        '/assign-subject',

        [
            InstructorManagementController::class,
            'assignSubject'
        ]
    );

});

//Route Notifications
Route::middleware([
    'auth:sanctum'
])

->group(function () {

    Route::get(

        '/notifications',

        [
            NotificationController::class,
            'index'
        ]
    );

    Route::post(

        '/notifications/{id}/read',

        [
            NotificationController::class,
            'markAsRead'
        ]
    );

    Route::delete(

        '/notifications/{id}',

        [
            NotificationController::class,
            'destroy'
        ]
    );

    //Route pour obtenir le nombre de notifications non lues
    Route::get(

        '/notifications/unread-count',

        [
            NotificationController::class,
            'unreadCount'
        ]
    );

});