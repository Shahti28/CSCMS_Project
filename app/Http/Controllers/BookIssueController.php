<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;
use App\Models\ActivityLog;

class BookIssueController extends Controller
{

public function create()
{
    $students = Student::all();
    $books = Book::where('available_quantity','>',0)
                 ->where('status', 'available')
                 ->get();

    return view('book_issues.create', compact('students','books'));
}

public function store(Request $request)
{
    $book = Book::findOrFail($request->book_id);

    // Validate BEFORE creating issue record
    if ($book->status !== 'available' || $book->available_quantity <= 0) {
        return redirect()->back()->with('error', 'This book is not available for issuing.');
    }

    $issueDate = $request->issue_date;
    $dueDate = date('Y-m-d', strtotime($issueDate . ' +7 days'));

    $issue = BookIssue::create([
        'student_id' => $request->student_id,
        'book_id' => $request->book_id,
        'issue_date' => $issueDate,
        'due_date' => $dueDate
    ]);

    $book->available_quantity -= 1;
    if ($book->status !== 'reserved' && $book->available_quantity == 0) {
        $book->status = 'issued';
    }
    $book->save();

    $student = Student::find($request->student_id);
    ActivityLog::create([
        'user' => session('user', 'System'),
        'action' => 'created',
        'module' => 'Library',
        'description' => "Issued book '{$book->title}' to student '{$student->name}'",
        'ip_address' => $request->ip()
    ]);

    return redirect('/books');
}

public function returnBook($id)
{
    $issue = BookIssue::findOrFail($id);

    $today = \Carbon\Carbon::now()->startOfDay();
    $dueDate = \Carbon\Carbon::parse($issue->due_date)->startOfDay();
    $issue->return_date = now();

    // Calculate fine: $2 per day late
    $fine = 0;
    if ($today->gt($dueDate)) {
        // Use dueDate->diffInDays(today) to always get a positive number
        $daysLate = (int) $dueDate->diffInDays($today);
        $fine = $daysLate * 2;
    }

    $issue->fine = $fine;
    $issue->save();

    // Log the fine (balance is computed dynamically from book_issues table)
    if ($fine > 0) {
        $student = Student::find($issue->student_id);
        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'updated',
            'module' => 'Finance',
            'description' => "Library fine of \${$fine} recorded for '{$student->name}' (Book return)",
            'ip_address' => request()->ip()
        ]);
    }

    $book = Book::findOrFail($issue->book_id);
    if ($book->available_quantity < $book->quantity) {
        $book->available_quantity += 1;
        if ($book->status !== 'reserved' && $book->available_quantity > 0) {
            $book->status = 'available';
        }
        $book->save();
    }

    ActivityLog::create([
        'user' => session('user', 'System'),
        'action' => 'updated',
        'module' => 'Library',
        'description' => "Book '{$book->title}' returned. Fine: \${$fine}",
        'ip_address' => request()->ip()
    ]);

    return redirect()->route('issue.index');
}

public function index()
{
    $issues = BookIssue::with(['student', 'book'])->latest()->get();

    return view('book_issues.index', compact('issues'));
}

}
