<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',
        'amount',
        'description',
        'payment_date',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'float'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
