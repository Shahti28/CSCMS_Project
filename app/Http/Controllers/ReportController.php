<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generatePdf(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $studentId = $request->get('student_id');

        $payments = Payment::with('student')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('payment_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('payment_date', '<=', $endDate);
            })
            ->when($studentId, function ($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            })
            ->orderBy('payment_date')
            ->get();

        $totalAmount = $payments->sum('amount');

        $pdf = Pdf::loadView('reports.pdf', compact('payments', 'totalAmount', 'startDate', 'endDate'));

        return $pdf->download('financial_report_' . now()->format('Y-m-d') . '.pdf');
    }

    public function generateCsv(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $studentId = $request->get('student_id');

        $payments = Payment::with('student')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('payment_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('payment_date', '<=', $endDate);
            })
            ->when($studentId, function ($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            })
            ->orderBy('payment_date')
            ->get();

        $filename = 'financial_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['ID', 'Student Name', 'Student ID', 'Type', 'Amount', 'Description', 'Payment Date', 'Status']);

            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->student->name,
                    $payment->student->student_id,
                    ucfirst(str_replace('_', ' ', $payment->type)),
                    $payment->amount,
                    $payment->description,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->status
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
