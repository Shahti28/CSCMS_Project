@extends('layouts.app')

@section('title', 'Library - Books List')
@section('page_title')
    <i class="fas fa-book me-2"></i>Library Management
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Books Inventory</h5>
        <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Book
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Qty</th>
                        <th>Available</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Fine</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td><small class="text-muted">{{ $book->isbn }}</small></td>
                        <td>{{ $book->quantity }}</td>
                        <td>
                            @if($book->available_quantity > 0)
                                <span class="badge bg-success">{{ $book->available_quantity }} Available</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                        @php
                            $latestIssue = $book->issues()->latest()->first();
                        @endphp

                        <td>
                            {{ $latestIssue ? $latestIssue->issue_date : 'N/A' }}
                        </td>

                        <td>
                            {{ $latestIssue ? $latestIssue->due_date : 'N/A' }}
                        </td>

                        <td>
                            @if($latestIssue && $latestIssue->return_date)
                                {{ \Carbon\Carbon::parse($latestIssue->return_date)->format('M d, Y h:i A') }}
                            @else
                                Not Returned
                            @endif
                        </td>

                        <td>
                            @if($latestIssue)
                                ${{ number_format($latestIssue->fine ?? 0, 2) }}
                            @else
                                $0.00
                            @endif
                        </td>

                        <td>
                            @if($latestIssue && $latestIssue->return_date)
                                <span class="badge bg-success">Returned</span>
                            @elseif($latestIssue)
                                <span class="badge bg-warning text-dark">Issued</span>
                            @else
                                <span class="badge bg-secondary">Never Issued</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($book->available_quantity > 0)
                                <a href="{{ route('issue.create', ['book_id' => $book->id]) }}" class="btn btn-info btn-sm text-white" title="Issue Book">
                                    <i class="fas fa-book-reader"></i>
                                </a>
                                @endif

                                @php
                                    $activeIssue = $book->issues()->whereNull('return_date')->latest()->first();
                                @endphp

                                @if($activeIssue)
                                    <a href="{{ url('/return-book/'.$activeIssue->id) }}" class="btn btn-success btn-sm" title="Return">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                @endif

                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
