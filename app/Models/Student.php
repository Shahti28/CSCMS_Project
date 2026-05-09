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

    // protected $casts = [
    //     'total_dues' => 'decimal:2'
    // ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getOutstandingBalanceAttribute()
    {
         return $this->total_dues - $this->payments()->sum('amount');
    }
}
