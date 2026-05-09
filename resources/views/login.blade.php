<!DOCTYPE html>
<html>
<head>
    <title>CSCMS - Sign In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5986 50%, #4fc3f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .login-card .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-card .brand i {
            font-size: 3rem;
            color: #1e3a5f;
        }
        .login-card .brand h3 {
            color: #1e3a5f;
            font-weight: 700;
            margin-top: 10px;
        }
        .login-card .brand small {
            color: #6c757d;
        }
        .form-control:focus {
            border-color: #4fc3f7;
            box-shadow: 0 0 0 0.2rem rgba(79,195,247,0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #1e3a5f, #2d5986);
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2d5986, #4fc3f7);
        }
        .credentials-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            font-size: 0.85rem;
        }
        .credentials-info .role-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .credentials-info .role-item:last-child { border: none; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand">
        <i class="fas fa-university"></i>
        <h3>CSCMS</h3>
        <small>Centralized Smart Campus Management System</small>
    </div>

    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
        </div>
        <button class="btn btn-primary btn-login w-100 text-white">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </button>
    </form>

    <div class="credentials-info">
        <p class="fw-bold mb-2 text-center"><i class="fas fa-info-circle me-1"></i>Demo Credentials</p>
        <div class="role-item">
            <span><i class="fas fa-user-shield text-primary me-1"></i>Admin</span>
            <span><code>admin / 12345</code></span>
        </div>
        <div class="role-item">
            <span><i class="fas fa-book text-success me-1"></i>Librarian</span>
            <span><code>librarian / 12345</code></span>
        </div>
        <div class="role-item">
            <span><i class="fas fa-calculator text-warning me-1"></i>Accountant</span>
            <span><code>accountant / 12345</code></span>
        </div>
    </div>
</div>

</body>
</html>
