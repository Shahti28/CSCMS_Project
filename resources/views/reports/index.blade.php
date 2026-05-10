@extends('layouts.app')

@section('title', 'Financial Reports')
@section('page_title')
    <i class="fas fa-file-invoice-dollar me-2"></i>Financial Reports
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom">
            <div class="card-header py-3">
                <h5 class="mb-0">Generate Financial Report</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.pdf') }}" method="GET">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Student (Optional)</label>
                            <select name="student_id" id="student_id" class="form-select">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-file-pdf me-1"></i> Download PDF Report
                        </button>
                        <button type="submit" formaction="{{ route('reports.csv') }}" class="btn btn-success px-4 py-2">
                            <i class="fas fa-file-csv me-1"></i> Download CSV Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-4 card card-custom bg-light border-0">
            <div class="card-body">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Report Instructions</h6>
                <p class="small text-muted mb-0">
                    Select a date range and optionally a specific student to generate a detailed financial report. 
                    The report includes all payment types (Tuition, Library Fines, Miscellaneous) and their current status.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
