@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page_title')
    <i class="fas fa-history me-2"></i>System Activity Logs
@endsection

@section('content')
<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Module</label>
                <select name="module" class="form-select">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">User</label>
                <input type="text" name="user" class="form-control" value="{{ request('user') }}" placeholder="Search user...">
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Logs Table -->
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</td>
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
                        <td>{{ $log->description }}</td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td><small class="text-muted">{{ $log->created_at ? $log->created_at->format('M d, Y H:i:s') : 'N/A' }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No activity logs found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection

</body>
</html>
