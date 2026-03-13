<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Welcome, {{ session('user') }}</h2>
    <a href="{{ url('/logout') }}" class="btn btn-danger mb-3">Sign Out</a>

    <div class="list-group">
        <a href="{{ url('/students') }}" class="list-group-item list-group-item-action">Student Management</a>
        <a href="{{ url('/books') }}" class="list-group-item list-group-item-action">Library Management</a>
    </div>
</div>
</body>
</html>
