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
                            @if($book->status == 'reserved')
                                <span class="badge bg-info text-white">Reserved</span>
                            @elseif($book->status == 'issued')
                                <span class="badge bg-warning text-dark">Issued</span>
                            @elseif($book->available_quantity > 0)
                                <span class="badge bg-success">{{ $book->available_quantity }} Available</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>

                        <td>
                            @if($book->status == 'reserved')
                                <span class="badge bg-info text-white">Reserved</span>
                            @elseif($book->status == 'issued')
                                <span class="badge bg-warning text-dark">Issued</span>
                            @elseif($book->status == 'available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unknown</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($book->available_quantity > 0 && $book->status === 'available')
                                <a href="{{ route('issue.create', ['book_id' => $book->id]) }}" class="btn btn-info btn-sm text-white" title="Issue Book">
                                    <i class="fas fa-book-reader"></i>
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
