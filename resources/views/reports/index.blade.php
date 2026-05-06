<!DOCTYPE html>
<html>
<head>
    <title>Financial Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Financial Reports</h2>
    <form action="{{ route('reports.pdf') }}" method="POST" class="mb-3">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="student_id" class="form-label">Student (Optional)</label>
                <select name="student_id" id="student_id" class="form-select">
                    <option value="">All Students</option>
                    @foreach(\App\Models\Student::all() as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Download PDF</button>
                <button type="submit" formaction="{{ route('reports.csv') }}" class="btn btn-secondary">Download CSV</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>