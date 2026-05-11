<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        
        // Self-healing: Ensure available_quantity is always in sync with actual issues
        foreach ($books as $book) {
            $actualIssued = $book->issues()->whereNull('return_date')->count();
            $correctAvailable = $book->quantity - $actualIssued;
            
            if ($book->available_quantity !== $correctAvailable) {
                $book->available_quantity = $correctAvailable;
                
                // Keep status consistent during self-healing
                if ($book->status !== 'reserved') {
                    if ($correctAvailable === 0) {
                        $book->status = 'issued';
                    } else {
                        $book->status = 'available';
                    }
                }
                
                $book->save();
            }
        }
        
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:available,issued,reserved'
        ]);

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'quantity' => $request->quantity,
            'available_quantity' => $request->quantity,
            'status' => $request->status
        ]);

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'created',
            'module' => 'Library',
            'description' => "Added new book: {$request->title}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('books.index')->with('success', 'Book added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $id,
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:available,issued,reserved'
        ]);
        
        // Calculate currently issued books from ACTUAL records, not the column
        $issuedCount = $book->issues()->whereNull('return_date')->count();
        
        // New available quantity should be new total minus currently issued
        $newAvailableQuantity = $request->quantity - $issuedCount;

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'quantity' => $request->quantity,
            'available_quantity' => max(0, $newAvailableQuantity),
            'status' => $request->status
        ]);

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'updated',
            'module' => 'Library',
            'description' => "Updated book: {$book->title} (Synced availability)",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $title = $book->title;
        $book->delete();

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'deleted',
            'module' => 'Library',
            'description' => "Deleted book: {$title}",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('books.index');
    }
}
