<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'department',
        'semester',
        'enrollment_status',
        'total_dues'
    ];

    protected $casts = [
        'total_dues' => 'float'
    ];

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Outstanding balance = total_dues (tuition etc.) + all returned book fines - all paid payments
     * This is the RECORDED balance (does not include unreturned overdue fines).
     */
    public function getOutstandingBalanceAttribute()
    {
        $totalFines = (float) $this->bookIssues()
            ->whereNotNull('return_date')
            ->where('fine', '>', 0)
            ->sum('fine');

        $paidAmount = (float) $this->payments()
            ->where('status', 'paid')
            ->sum('amount');

        $balance = (float) $this->total_dues + $totalFines - $paidAmount;
        return max(0, $balance);
    }

    /**
     * Calculated balance = outstanding_balance + live accrued fines for unreturned overdue books.
     * This is the TOTAL balance shown in the Students list.
     */
    public function getCalculatedBalanceAttribute()
    {
        $overdueFine = 0;
        $issues = $this->bookIssues()
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        foreach ($issues as $issue) {
            $dueDate = \Carbon\Carbon::parse($issue->due_date)->startOfDay();
            $today = \Carbon\Carbon::now()->startOfDay();
            if ($today->gt($dueDate)) {
                $daysLate = (int) $dueDate->diffInDays($today);
                $overdueFine += $daysLate * 2;
            }
        }

        return (float) $this->outstanding_balance + (float) $overdueFine;
    }
}
