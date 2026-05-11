@extends('layouts.app')

@section('title', 'Library - Issue Records')
@section('page_title')
    <i class="fas fa-history me-2"></i>Issue Records
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transaction History</h5>
        <a href="{{ route('issue.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Issue New Book
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Book</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Fine</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues as $issue)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $issue->student->name }}</div>
                            <small class="text-muted">{{ $issue->student->student_id }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $issue->book->title }}</div>
                            <small class="text-muted">ISBN: {{ $issue->book->isbn }}</small>
                        </td>
                        <td>{{ $issue->issue_date }}</td>
                        <td>{{ $issue->due_date }}</td>
                        <td>
                            @if($issue->return_date)
                                <span class="text-success">
                                    {{ \Carbon\Carbon::parse($issue->return_date)->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-danger">Not Returned</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $displayFine = $issue->fine;
                                $isPending = false;
                                if (!$issue->return_date) {
                                    $today = \Carbon\Carbon::now()->startOfDay();
                                    $dueDate = \Carbon\Carbon::parse($issue->due_date)->startOfDay();
                                    if ($today->gt($dueDate)) {
                                        $displayFine = (int) $dueDate->diffInDays($today) * 2;
                                        $isPending = true;
                                    }
                                }
                            @endphp
                            <span class="fw-bold {{ $displayFine > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($displayFine, 2) }}
                                @if($isPending)
                                    <small class="d-block text-muted" style="font-size: 0.65rem;">(Accrued)</small>
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($issue->return_date)
                                <span class="badge bg-success">Returned</span>
                            @else
                                <span class="badge bg-warning text-dark">Issued</span>
                            @endif
                        </td>
                        <td>
                            @if(!$issue->return_date)
                                <a href="{{ route('return.book', $issue->id) }}" class="btn btn-success btn-sm" title="Return Book">
                                    <i class="fas fa-undo me-1"></i> Return
                                </a>
                            @else
                                <span class="text-muted"><i class="fas fa-check-double"></i></span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
