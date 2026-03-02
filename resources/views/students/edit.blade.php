<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Student</h2>
    <a href="{{ url('/students') }}" class="btn btn-secondary mb-3">Back to List</a>

    <form action="{{ url('/students/'.$student->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Student ID</label>
            <input type="text" name="student_id" class="form-control" value="{{ $student->student_id }}" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
        </div>
        <div class="mb-3">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="{{ $student->department }}" required>
        </div>
        <div class="mb-3">
            <label>Semester</label>
            <input type="text" name="semester" class="form-control" value="{{ $student->semester }}" required>
        </div>
        <div class="mb-3">
            <label>Enrollment Status</label>
            <select name="enrollment_status" class="form-control" required>
                <option value="active" {{ $student->enrollment_status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $student->enrollment_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button class="btn btn-success">Update</button>
    </form>
</div>
</body>
</html>

