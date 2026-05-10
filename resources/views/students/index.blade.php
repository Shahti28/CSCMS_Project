@extends('layouts.app')

@section('title', 'Students List')
@section('page_title')
    <i class="fas fa-user-graduate me-2"></i>Students Management
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Students</h5>
        <a href="{{ url('/students/create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Student
        </a>
    </div>
    <div class="card-body p-0">

        <div class="p-3 border-bottom">
            <form method="GET" action="{{ route('students.index') }}">
                <div class="row g-2">

                    <div class="col-md-3">
                        <input type="text" name="search"
                            class="form-control"
                            placeholder="Search by Name or ID"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" name="department"
                            class="form-control"
                            placeholder="Department"
                            value="{{ request('department') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" name="semester"
                            class="form-control"
                            placeholder="Semester"
                            value="{{ request('semester') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="enrollment_status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('enrollment_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('enrollment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="graduated" {{ request('enrollment_status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>

                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $student->student_id }}</td>
                        <td>{{ $student->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $student->department }}</span></td>
                        <td>Semester {{ $student->semester }}</td>
                        <td>
                            @php
                                $statusClass = match(strtolower($student->enrollment_status)) {
                                    'active' => 'bg-success',
                                    'inactive' => 'bg-secondary',
                                    'graduated' => 'bg-info',
                                    'suspended' => 'bg-danger',
                                    default => 'bg-primary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($student->enrollment_status) }}</span>
                        </td>
                        <td class="fw-bold {{ $student->calculated_balance > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format($student->calculated_balance, 2) }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ url('/students/'.$student->id.'/edit') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ url('/students/'.$student->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">
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

