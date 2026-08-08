<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Instructor\QuizController;
use App\Http\Controllers\Student\HistoryController;
use App\Http\Controllers\Student\StatisticsController;
use App\Http\Controllers\Instructor\DashboardController;
use App\Http\Controllers\Instructor\StudentController;
use App\Http\Controllers\Student\QuizPlayController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole(['instructor_l1', 'instructor_l2'])) {
        return redirect()->route('instructor.dashboard');
    }

    if ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }

    abort(403);

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/profile/details', [\App\Http\Controllers\UserProfileController::class, 'update'])
        ->name('profile.details.update');
});

require __DIR__.'/auth.php';

//Route Admin matières
Route::middleware([
    'auth'
])

->prefix('admin')

->name('admin.')

->group(function(){

    Route::resource(
        'subjects',
        SubjectController::class
    )->except([
        'show'
    ]);

});

Route::middleware([
    'auth',
    'role:admin'
])

->prefix('admin')

->name('admin.')

->group(function(){

    Route::get(

        '/instructors',

        [
            \App\Http\Controllers\Admin\InstructorSubjectController::class,
            'index'
        ]

    )

    ->name(
        'instructors.index'
    );

    Route::put(

        '/instructors/{instructor}',

        [
            \App\Http\Controllers\Admin\InstructorSubjectController::class,
            'update'
        ]

    )

    ->name(
        'instructors.update'
    );

});

//Route promotion
Route::middleware([
    'auth'
])

->prefix('admin')

->name('admin.')

->group(function(){

    Route::resource(
        'promotions',
        PromotionController::class
    )->except([
        'show'
    ]);

    Route::resource(
        'users',
        UserController::class
    )->except([
        'create',
        'store'
    ]);

    Route::post(

        'users/{user}/activate',

        [
            UserController::class,
            'activate'
        ]

    )

    ->name(
        'users.activate'
    );

    Route::post(

        'users/{user}/deactivate',

        [
            UserController::class,
            'deactivate'
        ]

    )

    ->name(
        'users.deactivate'
    );

    Route::post(

        'users/{user}/reset-password',

        [
            UserController::class,
            'resetPassword'
        ]

    )

    ->name(
        'users.reset-password'
    );

});

//Instructeurs
Route::middleware([
    'auth'
])

->prefix('instructor')

->name('instructor.')

->group(function(){

    Route::resource(
        'quizzes',
        QuizController::class
    )->except([
        'show'
    ]);

    Route::post(
        'quizzes/{quiz}/questions',
        [\App\Http\Controllers\Instructor\QuestionController::class, 'store']
    )->name('quizzes.questions.store');

    Route::put(
        'quizzes/{quiz}/questions/{question}',
        [\App\Http\Controllers\Instructor\QuestionController::class, 'update']
    )->name('quizzes.questions.update');

    Route::delete(
        'quizzes/{quiz}/questions/{question}',
        [\App\Http\Controllers\Instructor\QuestionController::class, 'destroy']
    )->name('quizzes.questions.destroy');

});

//Group student
Route::prefix('student')

->name('student.')

->middleware(['auth'])

->group(function(){

    Route::get(

        '/dashboard',

        [
            \App\Http\Controllers\Student\DashboardController::class,
            'index'
        ]

    )

    ->name(
        'dashboard'
    );

    Route::get(

        '/quiz/configuration',

        [
            QuizPlayController::class,
            'configuration'
        ]

    )

    ->name(
        'quiz.configuration'
    );

    Route::post(

        '/quiz/start',

        [
            QuizPlayController::class,
            'start'
        ]

    )

    ->name(
        'quiz.start'
    );

    Route::get(

        '/quiz/{session}',

        [
            QuizPlayController::class,
            'show'
        ]

    )

    ->name(
        'quiz.show'
    );

    Route::post(

        '/quiz/{session}/submit',

        [
            QuizPlayController::class,
            'submit'
        ]

    )

    ->name(
        'quiz.submit'
    );

    Route::get(

        '/quiz/{session}/result',

        [
            QuizPlayController::class,
            'result'
        ]

    )

    ->name(
        'quiz.result'
    );
});

//Historique
Route::prefix('student')

->middleware(['auth'])

->name('student.')

->group(function(){

    Route::get(

        '/history',

        [
            HistoryController::class,
            'index'
        ]

    )

    ->name(
        'history.index'
    );

    Route::get(

        '/history/{session}',

        [
            HistoryController::class,
            'show'
        ]

    )

    ->name(
        'history.show'
    );

    Route::get(

        '/statistics',

        [
            StatisticsController::class,
            'index'
        ]

    )

    ->name(
        'statistics.index'
    );
});

Route::middleware([
    'auth',
    'track.activity'
])

->group(function(){

    // routes
});

Route::prefix('admin')

->middleware([
    'auth'
])

->name('admin.')

->group(function(){

    Route::get(

        '/dashboard',

        [
            AdminDashboardController::class,
            'index'
        ]

    )

    ->name(
        'dashboard'
    );

});

Route::prefix('instructor')

->middleware([
    'auth',
    'role:instructor_l1|instructor_l2'
])

->name('instructor.')

->group(function(){

    Route::get(

        '/dashboard',

        [
            DashboardController::class,
            'index'
        ]

    )

    ->name(
        'dashboard'
    );

    Route::get(

        '/students',

        [
            StudentController::class,
            'index'
        ]

    )

    ->name(
        'students.index'
    );

    Route::get(

        '/students/{student}/sessions',

        [
            StudentController::class,
            'sessions'
        ]

    )

    ->name(
        'students.sessions'
    );

    Route::delete(

        '/students/{student}/sessions/{session}',

        [
            StudentController::class,
            'resetSession'
        ]

    )

    ->name(
        'students.sessions.reset'
    );

});

Route::prefix('instructor')

->middleware([
    'auth',
    'role:instructor_l2'
])

->name('instructor.')

->group(function(){

    Route::get(

        '/l1-management',

        [
            \App\Http\Controllers\Instructor\InstructorManagementController::class,
            'index'
        ]

    )

    ->name(
        'l1-management.index'
    );

    Route::put(

        '/l1-management/{instructor}',

        [
            \App\Http\Controllers\Instructor\InstructorManagementController::class,
            'update'
        ]

    )

    ->name(
        'l1-management.update'
    );

});

