<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $payments = Payment::with('student')->orderByDesc('created_at')->get();
        return view('payments.index', compact('payments'));
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
        Payment::create([
            'student_id' => $request->student_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

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
