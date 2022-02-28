<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function fetchStudents()
    {
        $students = Student::all();
        return response()->json([
            'students' => $students
        ]);
    }

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'course' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors'=>$validator->messages(),
            ]);
        }else{
            Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'course' => $request->course
            ]);
            return response()->json([
                'status' => 200,
                'message'=>'Student added successfully'
            ]);
        }
    }

    public function edit($id)
    {
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status' => 200,
                'student' => $student
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Student not found'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'course' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $student = Student::find($id);
            if($student){
                $student->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'course' => $request->course
                ]);
                return response()->json([
                    'status' => 200,
                    'message'=>'Student updated successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Student not found'
                ]);
            }
        
        }
    }

    public function remove($id)
    {
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status' => 200,
                'student' => $student
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Student not found'
            ]);
        }
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        $student->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Student deleted successfully'
        ]);
    }
}
