@extends('layouts.app')

@section('title', 'Issue Book')
@section('page_title')
    <i class="fas fa-book-reader me-2"></i>Issue Book to Student
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header py-3">
                <h5 class="mb-0">Issue Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('issue.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Select Student</label>
                            <select name="student_id" class="form-select" required>
                                <option value="" selected disabled>Choose a student...</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->student_id }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Select Book</label>
                            <select name="book_id" class="form-select" required>
                                <option value="" selected disabled>Choose a book...</option>
                                @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} by {{ $book->author }} ({{ $book->available_quantity }} available)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-semibold">Issue Date</label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-check-circle me-1"></i> Issue Book
                        </button>
                        <a href="{{ url('/books') }}" class="btn btn-light border px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

