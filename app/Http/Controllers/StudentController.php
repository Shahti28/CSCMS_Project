<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
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
