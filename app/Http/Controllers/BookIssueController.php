<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;

class BookIssueController extends Controller
{

public function create()
{
    $students = Student::all();
    $books = Book::where('available_quantity','>',0)->get();

    return view('book_issues.create', compact('students','books'));
}

public function store(Request $request)
{

    $issueDate = $request->issue_date;
    $dueDate = date('Y-m-d', strtotime($issueDate . ' +7 days'));

    $issue = BookIssue::create([
        'student_id' => $request->student_id,
        'book_id' => $request->book_id,
        'issue_date' => $issueDate,
        'due_date' => $dueDate
    ]);

    $book = Book::find($request->book_id);
    if ($book->available_quantity > 0) {
        $book->available_quantity -= 1;
        $book->save();
    }


    return redirect('/books');
}

public function returnBook($id)
{
    $issue = BookIssue::findOrFail($id);

    $today = date('Y-m-d');
    $issue->return_date = $today;

    $fine = 0;

    if (strtotime($today) > strtotime($issue->due_date)) {
        $daysLate = floor((strtotime($today) - strtotime($issue->due_date)) / (60*60*24));
        $fine = $daysLate * 2; // fine per day
    }

    $issue->fine = $fine;
    $issue->save();

    $book = Book::findOrFail($issue->book_id);
    if ($book->available_quantity < $book->quantity) {
        $book->available_quantity += 1;
        $book->save();
    }


    return redirect('/books');
}

public function index()
{
    
    $books = Book::with('issues')->get();

    return view('books.index', compact('books'));
}

}

