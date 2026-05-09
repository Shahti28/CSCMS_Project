@extends('layouts.app')

@section('title', 'Analytics Dashboard')
@section('page_title')
    <i class="fas fa-chart-line me-2"></i>Analytics Dashboard
@endsection

@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .stat-card { border: none; border-radius: 15px; padding: 25px; color: #fff; transition: transform 0.3s, box-shadow 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .stat-card .stat-icon { font-size: 2.5rem; opacity: 0.8; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #1a5c3a !important; }
        .bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-gradient-dark { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
        .bg-gradient-teal { background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); }
        .bg-gradient-orange { background: linear-gradient(135deg, #fc6076 0%, #ff9a44 100%); }
        .badge-module { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; }
    </style>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Students</div>
                        <div class="stat-value">{{ $totalStudents }}</div>
                        <small>{{ $activeStudents }} active</small>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Books</div>
                        <div class="stat-value">{{ $totalBooks }}</div>
                        <small>{{ $availableBooks }} available</small>
                    </div>
                    <div class="stat-icon"><i class="fas fa-book"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Issued Books</div>
                        <div class="stat-value">{{ $issuedBooks }}</div>
                        <small>{{ $overdueBooks }} overdue</small>
                    </div>
                    <div class="stat-icon"><i class="fas fa-book-reader"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Payments</div>
                        <div class="stat-value">${{ number_format($totalPayments, 2) }}</div>
                        <small>Fines: ${{ number_format($totalFines, 2) }}</small>
                    </div>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-chart-area me-2 text-primary"></i>Payment Trends
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-chart-bar me-2 text-success"></i>Library Usage Trends
                </div>
                <div class="card-body">
                    <canvas id="libraryChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Department & Payment Type Charts -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-chart-pie me-2 text-info"></i>Students by Department
                </div>
                <div class="card-body">
                    <canvas id="deptChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-chart-pie me-2 text-warning"></i>Payments by Type
                </div>
                <div class="card-body">
                    <canvas id="paymentTypeChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-chart-bar me-2 text-danger"></i>Dept-wise Book Issues
                </div>
                <div class="card-body">
                    <canvas id="deptIssueChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-fire me-2 text-danger"></i>Most Borrowed Books
                </div>
                <div class="card-body p-0">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Times Borrowed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularBooks as $index => $book)
                            <tr>
                                <td>
                                    @if($index == 0) <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i></span>
                                    @elseif($index == 1) <span class="badge bg-secondary"><i class="fas fa-medal"></i></span>
                                    @elseif($index == 2) <span class="badge bg-danger"><i class="fas fa-award"></i></span>
                                    @else {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td><span class="badge bg-primary rounded-pill">{{ $book->borrow_count }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No book issue records yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-history me-2 text-info"></i>Recent Activity</span>
                    @if(session('role') === 'admin')
                    <a href="{{ url('/activity-logs') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr>
                                <td class="fw-semibold">{{ $log->user }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($log->action) {
                                            'login' => 'bg-success',
                                            'logout' => 'bg-secondary',
                                            'created' => 'bg-primary',
                                            'updated' => 'bg-warning text-dark',
                                            'deleted' => 'bg-danger',
                                            default => 'bg-info'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} badge-module">{{ $log->action }}</span>
                                </td>
                                <td>{{ $log->module }}</td>
                                <td><small class="text-muted">{{ $log->created_at ? $log->created_at->diffForHumans() : 'N/A' }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No activity logs yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Department-wise Statistics Table -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header py-3">
                    <i class="fas fa-building me-2 text-primary"></i>Department-wise Statistics
                </div>
                <div class="card-body p-0">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Students</th>
                                <th>Books Issued</th>
                                <th>% of Total Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departmentStats as $dept)
                            <tr>
                                <td class="fw-semibold">{{ $dept->department }}</td>
                                <td>{{ $dept->student_count }}</td>
                                <td>
                                    @php
                                        $deptIssue = $deptBookIssues->firstWhere('department', $dept->department);
                                    @endphp
                                    {{ $deptIssue ? $deptIssue->issue_count : 0 }}
                                </td>
                                <td>
                                    @php $pct = $totalStudents > 0 ? round(($dept->student_count / $totalStudents) * 100, 1) : 0; @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $pct }}%">{{ $pct }}%</div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No department data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Payment Trends Chart
const paymentCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(paymentCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($paymentTrends->pluck('month')) !!},
        datasets: [{
            label: 'Payment Amount ($)',
            data: {!! json_encode($paymentTrends->pluck('total')) !!},
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true, position: 'top' } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});

// Library Usage Trends Chart
const libraryCtx = document.getElementById('libraryChart').getContext('2d');
new Chart(libraryCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($libraryTrends->pluck('month')) !!},
        datasets: [{
            label: 'Books Issued',
            data: {!! json_encode($libraryTrends->pluck('total')) !!},
            backgroundColor: 'rgba(67, 233, 123, 0.7)',
            borderColor: '#43e97b',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true, position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});

// Department Distribution Pie Chart
const deptCtx = document.getElementById('deptChart').getContext('2d');
new Chart(deptCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($departmentStats->pluck('department')) !!},
        datasets: [{
            data: {!! json_encode($departmentStats->pluck('student_count')) !!},
            backgroundColor: ['#667eea', '#43e97b', '#f093fb', '#4facfe', '#fa709a', '#30cfd0', '#ff9a44', '#a18cd1'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
        }
    }
});

// Payment Type Distribution
const payTypeCtx = document.getElementById('paymentTypeChart').getContext('2d');
new Chart(payTypeCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentByType->pluck('type')->map(function($t) { return ucfirst(str_replace('_', ' ', $t)); })) !!},
        datasets: [{
            data: {!! json_encode($paymentByType->pluck('total')) !!},
            backgroundColor: ['#f5576c', '#4facfe', '#43e97b', '#ff9a44'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
        }
    }
});

// Department-wise Book Issues Bar Chart
const deptIssueCtx = document.getElementById('deptIssueChart').getContext('2d');
new Chart(deptIssueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($deptBookIssues->pluck('department')) !!},
        datasets: [{
            label: 'Books Issued',
            data: {!! json_encode($deptBookIssues->pluck('issue_count')) !!},
            backgroundColor: ['#fa709a', '#667eea', '#43e97b', '#4facfe', '#f093fb', '#30cfd0'],
            borderRadius: 8,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endsection
