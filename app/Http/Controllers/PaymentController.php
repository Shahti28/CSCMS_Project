<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $studentId = $request->get('student_id');
        $payments = Payment::with('student')
            ->when($studentId, function ($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            })
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        $students = Student::all();

        return view('payments.index', compact('payments', 'students', 'studentId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all();
        return view('payments.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
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

        $payment = Payment::create($request->all());

        // Log the transaction
        Log::info('Payment recorded', [
            'payment_id' => $payment->id,
            'student_id' => $payment->student_id,
            'amount' => $payment->amount,
            'type' => $payment->type,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $students = Student::all();
        return view('payments.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:tuition,library_fine,miscellaneous',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_date' => 'required|date',
            'status' => 'required|in:paid,pending'
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully');
    }
}
