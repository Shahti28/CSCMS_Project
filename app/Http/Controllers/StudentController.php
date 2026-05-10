<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\BookIssue;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('enrollment_status')) {
            $query->where('enrollment_status', $request->enrollment_status);
        }

        $students = $query->get();

        foreach ($students as $student) {
        $overdueFine = 0;

        $issues = BookIssue::where('student_id', $student->id)
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now())
            ->get();

        foreach ($issues as $issue) {
            $daysLate = Carbon::parse($issue->due_date)->diffInDays(now());
            $overdueFine += $daysLate * 2; // $2 fine per day
        }

        $student->calculated_balance = $student->outstanding_balance + $overdueFine;
    }

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'enrollment_status' => 'required|in:active,inactive,graduated,suspended'
        ]);

        Student::create($request->all());

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'created',
            'module' => 'Students',
            'description' => "Added new student: {$request->name}",
            'ip_address' => $request->ip()
        ]);

        return redirect('/students')->with('success', 'Student added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'enrollment_status' => 'required|in:active,inactive,graduated,suspended'
        ]);

        $student->update($request->all());

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'updated',
            'module' => 'Students',
            'description' => "Updated student: {$student->name}",
            'ip_address' => $request->ip()
        ]);

        return redirect('/students')->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $name = $student->name;
        $student->delete();

        ActivityLog::create([
            'user' => session('user', 'System'),
            'action' => 'deleted',
            'module' => 'Students',
            'description' => "Deleted student: {$name}",
            'ip_address' => request()->ip()
        ]);

        return redirect('/students')->with('success', 'Student deleted successfully');
    
    }
}
