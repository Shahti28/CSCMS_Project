<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
        $validated = $request->validate([
            'student_id' => 'required',
            'name' => 'required|string',
            'department' => 'required|string',
            'semester' => 'required',
            'enrollment_status' => 'required',
            'total_dues' => 'required|numeric|min:0',
        ]);

        Student::create($validated);
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
        $validated = $request->validate([
            'student_id' => 'required',
            'name' => 'required|string',
            'department' => 'required|string',
            'semester' => 'required',
            'enrollment_status' => 'required',
            'total_dues' => 'required|numeric|min:0',
        ]);

        $student->update($validated);
        return redirect('/students')->with('success', 'Student updated successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect('/students')->with('success', 'Student deleted successfully');
    
    }
}
