<!DOCTYPE html>
<html>
<head>
    <title>Edit Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Payment</h2>
    <form action="{{ route('payments.update', $payment) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="form-select" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ $payment->student_id == $student->id ? 'selected' : '' }}>
                        {{ $student->name }} ({{ $student->student_id }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Payment Type</label>
            <select name="payment_type" id="payment_type" class="form-select" required>
               <option value="tuition" {{ $payment->payment_type == 'tuition' ? 'selected' : '' }}>Tuition Fees</option>

                <option value="library_fine" {{ $payment->payment_type == 'library_fine' ? 'selected' : '' }}>Library Fine</option>

                <option value="miscellaneous" {{ $payment->payment_type == 'miscellaneous' ? 'selected' : '' }}>Miscellaneous Fees</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $payment->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $payment->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $payment->payment_date->format('Y-m-d') }}" required>
        </div>
        <!-- <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="paid" {{ $payment->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div> -->
        <button type="submit" class="btn btn-primary">Update Payment</button>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>resources/views/payments/edit.blade.php