@extends('layouts.app')

@section('title', 'Edit Payment')
@section('page_title')
    <i class="fas fa-edit me-2"></i>Edit Payment Record
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header py-3">
                <h5 class="mb-0">Modify Payment: #{{ $payment->id }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Student</label>
                            <select name="student_id" class="form-select" required>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $payment->student_id == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->student_id }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Type</label>
                            <select name="type" class="form-select" required>
                                <option value="tuition" {{ $payment->type == 'tuition' ? 'selected' : '' }}>Tuition Fees</option>
                                <option value="library_fine" {{ $payment->type == 'library_fine' ? 'selected' : '' }}>Library Fine</option>
                                <option value="miscellaneous" {{ $payment->type == 'miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Amount ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ $payment->amount }}" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $payment->description }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" required value="{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="paid" {{ $payment->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-sync-alt me-1"></i> Update Payment
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-light border px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
