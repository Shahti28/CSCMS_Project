<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main analytics dashboard (Feature 16).
     */
    public function index()
    {
        // Key statistics
        $totalStudents = Student::count();
        $totalBooks = Book::sum('quantity');
        $issuedBooks = BookIssue::whereNull('return_date')->count();
        $totalPayments = Payment::sum('amount');
        $totalFines = BookIssue::sum('fine');
        $availableBooks = Book::sum('available_quantity');
        $activeStudents = Student::where('enrollment_status', 'active')->count();
        $overdueBooks = BookIssue::whereNull('return_date')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        // Monthly payment trends for chart (Feature 17)
        $paymentTrends = Payment::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        // Monthly library usage trends (Feature 17)
        $libraryTrends = BookIssue::select(
                DB::raw("DATE_FORMAT(issue_date, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        // Most frequently borrowed books (Feature 18)
        $popularBooks = Book::select('books.id', 'books.title', 'books.author', DB::raw('COUNT(book_issues.id) as borrow_count'))
            ->leftJoin('book_issues', 'books.id', '=', 'book_issues.book_id')
            ->groupBy('books.id', 'books.title', 'books.author')
            ->orderByDesc('borrow_count')
            ->limit(5)
            ->get();

        // Department-wise statistics (Feature 18)
        $departmentStats = Student::select('department', DB::raw('COUNT(*) as student_count'))
            ->groupBy('department')
            ->orderByDesc('student_count')
            ->get();

        // Department-wise book issues
        $deptBookIssues = Student::select('students.department', DB::raw('COUNT(book_issues.id) as issue_count'))
            ->leftJoin('book_issues', 'students.id', '=', 'book_issues.student_id')
            ->groupBy('students.department')
            ->orderByDesc('issue_count')
            ->get();

        // Payment by type breakdown
        $paymentByType = Payment::select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get();

        // Recent activity logs (Feature 19)
        $recentLogs = ActivityLog::orderByDesc('created_at')->limit(10)->get();

        // Log this dashboard visit
        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'viewed',
            'module' => 'Dashboard',
            'description' => 'Accessed the analytics dashboard',
            'ip_address' => request()->ip()
        ]);

        return view('dashboard', compact(
            'totalStudents',
            'totalBooks',
            'issuedBooks',
            'totalPayments',
            'totalFines',
            'availableBooks',
            'activeStudents',
            'overdueBooks',
            'paymentTrends',
            'libraryTrends',
            'popularBooks',
            'departmentStats',
            'deptBookIssues',
            'paymentByType',
            'recentLogs'
        ));
    }

    /**
     * API endpoint for real-time dashboard stats refresh.
     */
    public function stats()
    {
        return response()->json([
            'totalStudents' => Student::count(),
            'totalBooks' => Book::sum('quantity'),
            'issuedBooks' => BookIssue::whereNull('return_date')->count(),
            'totalPayments' => Payment::sum('amount'),
        ]);
    }
}
