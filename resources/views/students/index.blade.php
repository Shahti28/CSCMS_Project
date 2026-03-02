<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Students List</h2>
    <a href="{{ url('/students/create') }}" class="btn btn-primary mb-3">Add Student</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Enrollment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->department }}</td>
                <td>{{ $student->semester }}</td>
                <td>{{ $student->enrollment_status }}</td>
                <td>
                    <a href="{{ url('/students/'.$student->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ url('/students/'.$student->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

