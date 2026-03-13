<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'book_id',
        'issue_date',
        'due_date',
        'return_date',
        'fine'
    ];
}

