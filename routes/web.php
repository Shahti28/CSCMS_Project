<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookIssueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Public routes
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware(['auth.check'])->group(function () {

    // Dashboard - accessible by all roles (Feature 16, 17, 18)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Student Management - Admin only (Feature 20)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('students', StudentController::class);
    });

    // Library Management - Admin and Librarian (Feature 20)
    Route::middleware(['role:admin,librarian'])->group(function () {
        Route::resource('books', BookController::class);
        Route::get('/issue-book', [BookIssueController::class, 'create'])->name('issue.create');
        Route::post('/issue-book', [BookIssueController::class, 'store'])->name('issue.store');
        Route::get('/return-book/{id}', [BookIssueController::class, 'returnBook'])->name('return.book');
    });

    // Payment Management - Admin and Accountant (Feature 20)
    Route::middleware(['role:admin,accountant'])->group(function () {
        Route::resource('payments', PaymentController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [ReportController::class, 'generatePdf'])->name('reports.pdf');
        Route::get('/reports/csv', [ReportController::class, 'generateCsv'])->name('reports.csv');
    });

    // Activity Logs - Admin only (Feature 19)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });
});
