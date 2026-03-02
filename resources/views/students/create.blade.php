<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Student</h2>
    <a href="{{ url('/students') }}" class="btn btn-secondary mb-3">Back to List</a>

    <form action="{{ url('/students') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Student ID</label>
            <input type="text" name="student_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Department</label>
            <input type="text" name="department" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Semester</label>
            <input type="text" name="semester" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Enrollment Status</label>
            <select name="enrollment_status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button class="btn btn-success">Save</button>
    </form>
</div>
</body>
</html>

