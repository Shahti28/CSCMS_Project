<!DOCTYPE html>
<html>
<head>
    <title>Payment Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Payment Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Payment #{{ $payment->id }}</h5>
            <p><strong>Student:</strong> {{ $payment->student->name }} ({{ $payment->student->student_id }})</p>
            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $payment->type)) }}</p>
            <p><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
            <p><strong>Description:</strong> {{ $payment->description ?: 'N/A' }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date->format('Y-m-d') }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'warning' }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </p>
            <p><strong>Created At:</strong> {{ $payment->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Updated At:</strong> {{ $payment->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary mt-3">Back to Payments</a>
</div>
</body>
</html>