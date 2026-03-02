<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware(['auth.check'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::resource('students', StudentController::class);

    // Route::resource('library', LibraryController::class);
    // Route::resource('finance', FinanceController::class);
});

// Route::get('/students', [StudentController::class, 'index']);
// Route::get('/students/create', [StudentController::class, 'create']);
// Route::post('/students', [StudentController::class, 'store']);
// Route::get('/students/{student}/edit', [StudentController::class, 'edit']);
// Route::put('/students/{student}', [StudentController::class, 'update']);
// Route::delete('/students/{student}', [StudentController::class, 'destroy']);






