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
        'enrollment_status'
    ];

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
