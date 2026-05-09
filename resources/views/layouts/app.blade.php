<!DOCTYPE html>
<html>
<head>
    <title>CSCMS - @yield('title', 'Campus Management System')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: linear-gradient(135deg, #1e3a5f 0%, #2d5986 100%); min-height: 100vh; padding: 0; position: fixed; width: 250px; z-index: 1000; }
        .sidebar .brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar .brand h4 { color: #fff; margin: 0; font-weight: 700; letter-spacing: 1px; }
        .sidebar .brand small { color: rgba(255,255,255,0.6); font-size: 11px; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 20px; border-left: 3px solid transparent; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); border-left-color: #4fc3f7; }
        .sidebar .nav-link i { width: 25px; margin-right: 10px; }
        .main-content { margin-left: 250px; padding: 20px 30px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); overflow: hidden; }
        .card-custom .card-header { background: #fff; border-bottom: 2px solid #f0f2f5; font-weight: 600; }
        .top-bar { background: #fff; padding: 15px 30px; margin: -20px -30px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; }
        .role-badge { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; padding: 3px 12px; border-radius: 20px; font-size: 0.75rem; text-transform: capitalize; }
        .table-custom thead th { background: #f8f9fa; color: #495057; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; border-top: none; }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="brand">
        <h4><i class="fas fa-university"></i> CSCMS</h4>
        <small>Campus Management System</small>
    </div>
    <nav class="nav flex-column mt-3">
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ url('/dashboard') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>

        @if(session('role') === 'admin')
        <a class="nav-link {{ Request::is('students*') ? 'active' : '' }}" href="{{ url('/students') }}"><i class="fas fa-user-graduate"></i> Students</a>
        @endif

        @if(in_array(session('role'), ['admin', 'librarian']))
        <a class="nav-link {{ Request::is('books*') ? 'active' : '' }}" href="{{ url('/books') }}"><i class="fas fa-book"></i> Library</a>
        <a class="nav-link {{ Request::is('issue*') ? 'active' : '' }}" href="{{ route('issue.create') }}"><i class="fas fa-book-reader"></i> Issue Book</a>
        @endif

        @if(in_array(session('role'), ['admin', 'accountant']))
        <a class="nav-link {{ Request::is('payments*') ? 'active' : '' }}" href="{{ url('/payments') }}"><i class="fas fa-money-bill-wave"></i> Payments</a>
        @endif

        @if(session('role') === 'admin')
        <a class="nav-link {{ Request::is('activity-logs*') ? 'active' : '' }}" href="{{ url('/activity-logs') }}"><i class="fas fa-history"></i> Activity Logs</a>
        @endif

        <a class="nav-link mt-4" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h5 class="mb-0">@yield('page_title')</h5>
        <div class="user-info">
            <span class="role-badge">{{ session('role', 'user') }}</span>
            <div class="user-avatar">{{ strtoupper(substr(session('user', 'U'), 0, 1)) }}</div>
            <span class="fw-semibold">{{ ucfirst(session('user', 'User')) }}</span>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
