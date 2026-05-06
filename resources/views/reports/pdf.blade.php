<!DOCTYPE html>
<html>
<head>
    <title>Financial Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Financial Report</h1>
    @if($startDate || $endDate)
        <p>Period: {{ $startDate ?: 'Beginning' }} to {{ $endDate ?: 'End' }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Payment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->student->name }}</td>
                <td>{{ $payment->student->student_id }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->type)) }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->description }}</td>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                <td>{{ ucfirst($payment->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">Total Amount</td>
                <td class="total">${{ number_format($totalAmount, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>