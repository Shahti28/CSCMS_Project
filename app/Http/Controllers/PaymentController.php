<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\BookIssue;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $studentId = $request->get('student_id');
        $payments = Payment::with('student')
            ->when($studentId, function ($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            })
            ->orderByDesc('payment_date')
            ->paginate(10);
            
        $students = Student::all();
        return view('payments.index', compact('payments', 'students', 'studentId'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $students = Student::all();
        return view('payments.create', compact('students'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:tuition,library_fine,miscellaneous',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending'
        ]);

        $payment = Payment::create([
            'student_id' => $request->student_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
        ]);

        if ($request->type === 'library_fine' && $request->status === 'paid') {
            $remainingPayment = $request->amount;

            $issues = BookIssue::where('student_id', $request->student_id)
                ->where('fine', '>', 0)
                ->orderBy('return_date')
                ->get();

            foreach ($issues as $issue) {
                if ($remainingPayment <= 0) {
                    break;
                }

                if ($remainingPayment >= $issue->fine) {
                    $remainingPayment -= $issue->fine;
                    $issue->fine = 0;
                } else {
                    $issue->fine -= $remainingPayment;
                    $remainingPayment = 0;
                }

                $issue->save();
            }
        }

        // Log the activity
        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'created',
            'module' => 'Payments',
            'description' => "Recorded a payment of \${$request->amount} ({$request->type})",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully');
    }

    /**
     * Display the specified payment.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $students = Student::all();
        return view('payments.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:tuition,library_fine,miscellaneous',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending'
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->all());

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'updated',
            'module' => 'Payments',
            'description' => "Updated payment #{$id}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully');
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'deleted',
            'module' => 'Payments',
            'description' => "Deleted payment #{$id}",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully');
    }
}
