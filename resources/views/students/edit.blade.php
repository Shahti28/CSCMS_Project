@extends('layouts.app')

@section('title', 'Edit Student')
@section('page_title')
    <i class="fas fa-user-edit me-2"></i>Edit Student Details
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header py-3">
                <h5 class="mb-0">Modify Student: {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ url('/students/'.$student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Student ID</label>
                            <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id', $student->student_id) }}" required>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Department</label>
                            <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $student->department) }}" required>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Semester</label>
                            <input type="text" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $student->semester) }}" required>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Enrollment Status</label>
                            <select name="enrollment_status" class="form-select @error('enrollment_status') is-invalid @enderror" required>
                                <option value="active" {{ old('enrollment_status', $student->enrollment_status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('enrollment_status', $student->enrollment_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="graduated" {{ old('enrollment_status', $student->enrollment_status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="suspended" {{ old('enrollment_status', $student->enrollment_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('enrollment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Total Dues ($)</label>
                            <input type="number" step="0.01" name="total_dues" class="form-control" value="{{ old('total_dues', $student->total_dues) }}" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-sync-alt me-1"></i> Update Student
                        </button>
                        <a href="{{ url('/students') }}" class="btn btn-light border px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

