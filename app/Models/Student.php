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
        'total_dues' => 'decimal:2'
    ];

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getOutstandingBalanceAttribute()
    {
        $balance = $this->total_dues - $this->payments()->where('status', 'paid')->sum('amount');
        return max(0, $balance);
    }
}
