<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #4e73df;
            font-size: 24px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e3e6f0;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fc;
            color: #4e73df;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .total-row {
            background-color: #f8f9fc;
            font-weight: bold;
        }
        .status-paid {
            color: #1cc88a;
        }
        .status-pending {
            color: #f6c23e;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #858796;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CSCMS Financial Report</h1>
        <p>Generated on {{ date('M d, Y H:i:s') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Period:</strong> {{ $startDate ?: 'Beginning' }} to {{ $endDate ?: 'End' }}</td>
                <td style="text-align: right;"><strong>Total Records:</strong> {{ count($payments) }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $payment->student->name }}</td>
                <td>{{ $payment->student->student_id }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->type)) }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'N/A' }}</td>
                <td class="status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total Amount:</td>
                <td colspan="3">${{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Computer Science Course Management System (CSCMS) - Official Document</p>
    </div>
</body>
</html>
