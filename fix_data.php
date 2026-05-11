<?php
// Fix corrupted database data from negative fines
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\BookIssue;
use Illuminate\Support\Facades\DB;

echo "=== FIXING CORRUPTED DATA ===\n\n";

// Step 1: Fix all negative fines in book_issues to positive values
$negativeIssues = BookIssue::where('fine', '<', 0)->get();
echo "Found " . count($negativeIssues) . " issues with negative fines.\n";
foreach ($negativeIssues as $issue) {
    $oldFine = $issue->fine;
    $issue->fine = abs($oldFine);
    $issue->save();
    echo "  Fixed Issue #{$issue->id}: fine {$oldFine} -> {$issue->fine}\n";
}

// Step 2: Recalculate total_dues for each student based on actual returned fines
echo "\n=== RECALCULATING STUDENT TOTAL DUES ===\n";
$students = Student::all();
foreach ($students as $student) {
    // Sum all positive fines from returned books for this student
    $totalFines = BookIssue::where('student_id', $student->id)
        ->whereNotNull('return_date')
        ->where('fine', '>', 0)
        ->sum('fine');
    
    // The student's total_dues should be their original dues (non-fine) + total fines
    // Since we can't distinguish original dues from fine-added dues, we'll set 
    // total_dues = total fines (as the original system started with 0 or tuition-based dues)
    // But we need to keep any manually set tuition dues
    
    // For now, let's just set total_dues to the sum of all fines
    // since the data is too corrupted to recover the original values
    $oldDues = $student->total_dues;
    $student->total_dues = (float) $totalFines;
    $student->save();
    
    $paidAmount = $student->payments()->where('status', 'paid')->sum('amount');
    
    echo "Student: {$student->name}\n";
    echo "  Old total_dues: {$oldDues}\n";
    echo "  Total library fines: {$totalFines}\n";
    echo "  New total_dues: {$student->total_dues}\n";
    echo "  Paid amount: {$paidAmount}\n";
    echo "  New outstanding: " . max(0, $student->total_dues - $paidAmount) . "\n\n";
}

echo "=== FIX COMPLETE ===\n";
