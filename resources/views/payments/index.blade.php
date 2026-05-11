@extends('layouts.app')

@section('title', 'Payments')

@section('page_title')
    <i class="fas fa-money-bill-wave me-2"></i>Payment Management
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment Records</h5>
        <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Record Payment
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('payments.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Filter by Student</label>
                <select name="student_id" class="form-select">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ ($studentId ?? '') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->student_id }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>

        @if($studentId)
            @php $selectedStudent = $students->find($studentId); @endphp
            @if($selectedStudent)
            <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="mb-1 text-uppercase small fw-bold">Student Balance Overview</h6>
                    <p class="mb-0">Current outstanding balance for <strong>{{ $selectedStudent->name }}</strong></p>
                </div>
                <div class="text-end">
                    <h4 class="mb-0 text-danger fw-bold">${{ number_format($selectedStudent->calculated_balance, 2) }}</h4>
                    <small class="text-muted">Including recorded dues and accrued fines</small>
                </div>
            </div>
            @endif
        @endif

        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $payment->student ? $payment->student->name : 'N/A' }}</td>
                        <td>
                            @php
                                $typeClass = match(strtolower($payment->type)) {
                                    'tuition' => 'bg-primary',
                                    'fine' => 'bg-danger',
                                    'library' => 'bg-info',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $typeClass }}">{{ ucfirst(str_replace('_', ' ', $payment->type)) }}</span>
                        </td>
                        <td class="fw-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $payment->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No payment records yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
