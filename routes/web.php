<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookIssueController;

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
    Route::resource('books', BookController::class);
    Route::get('/issue-book', [BookIssueController::class, 'create'])->name('issue.create');
    Route::post('/issue-book', [BookIssueController::class, 'store'])->name('issue.store');
    Route::get('/return-book/{id}', [App\Http\Controllers\BookIssueController::class, 'returnBook'])->name('return.book');

});







