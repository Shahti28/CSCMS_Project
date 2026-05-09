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
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Date</th>
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
                        <td>{{ $payment->description ?? '-' }}</td>
                        <td>{{ $payment->created_at ? $payment->created_at->format('M d, Y') : 'N/A' }}</td>
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
                    <tr><td colspan="7" class="text-center text-muted py-4">No payment records yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
