@extends('layouts.app')

@section('title', 'Record Payment')
@section('page_title')
    <i class="fas fa-plus-circle me-2"></i>Record New Payment
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header py-3">
                <h5 class="mb-0">Payment Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Student</label>
                            <select name="student_id" class="form-select" required>
                                <option value="" selected disabled>Choose a student...</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Type</label>
                            <select name="type" class="form-select" required>
                                <option value="tuition">Tuition Fees</option>
                                <option value="library_fine">Library Fine</option>
                                <option value="miscellaneous">Miscellaneous</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Amount ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Record Payment
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-light border px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
