<?php
// Debug script to check the database state
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\BookIssue;
use App\Models\Payment;

echo "=== ALL STUDENTS ===\n";
$students = Student::all();
foreach ($students as $s) {
    $paidAmount = $s->payments()->where('status', 'paid')->sum('amount');
    echo "Student: {$s->name} (ID: {$s->id})\n";
    echo "  total_dues in DB: {$s->total_dues}\n";
    echo "  paid amount: {$paidAmount}\n";
    echo "  outstanding_balance (accessor): {$s->outstanding_balance}\n";
    echo "  calculated_balance (accessor): {$s->calculated_balance}\n";
    echo "\n";
}

echo "=== ALL BOOK ISSUES ===\n";
$issues = BookIssue::all();
foreach ($issues as $i) {
    echo "Issue #{$i->id}: student_id={$i->student_id}, book_id={$i->book_id}, ";
    echo "issue_date={$i->issue_date}, due_date={$i->due_date}, ";
    echo "return_date={$i->return_date}, fine={$i->fine}\n";
}

echo "\n=== ALL PAYMENTS ===\n";
$payments = Payment::all();
foreach ($payments as $p) {
    echo "Payment #{$p->id}: student_id={$p->student_id}, type={$p->type}, ";
    echo "amount={$p->amount}, status={$p->status}\n";
}

echo "\n=== TEST: diffInDays calculation ===\n";
$today = \Carbon\Carbon::now()->startOfDay();
$pastDate = \Carbon\Carbon::parse('2026-05-01')->startOfDay();
echo "today: {$today}\n";
echo "pastDate: {$pastDate}\n";
echo "today->diffInDays(pastDate): " . $today->diffInDays($pastDate) . "\n";
echo "pastDate->diffInDays(today): " . $pastDate->diffInDays($today) . "\n";
echo "today->gt(pastDate): " . ($today->gt($pastDate) ? 'true' : 'false') . "\n";
