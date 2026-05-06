<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Payments</h2>
    <a href="{{ route('payments.create') }}" class="btn btn-primary mb-3">Record Payment</a>

    <form method="GET" action="{{ route('payments.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="student_id" class="form-select">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $studentId == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->student_id }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->student->name }} ({{ $payment->student->student_id }})</td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->type)) }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->description }}</td>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                <td>
                    <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $payments->links() }}
</div>
</body>
</html>