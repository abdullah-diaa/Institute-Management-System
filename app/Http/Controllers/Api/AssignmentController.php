<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Course;
use App\Http\Requests\AssignmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $assignmentsQuery = Assignment::query();
        $courseId = $request->input('course_id');
        

        if ($user->role !== 'admin') {

            //showing the students only the courses the are enrolled in
            $assignmentsQuery->whereHas('course.students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        if ($courseId) {
            $assignmentsQuery->where('course_id', $courseId);
        }

        $assignments = $assignmentsQuery->orderBy('created_at', 'desc')->get();
        return response()->json($assignments);
    }

    public function store(AssignmentRequest $request)
    {
        $assignment = new Assignment($request->validated());
        $assignment->user_id = Auth::id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/assignments_files', $fileName);
            $assignment->file = 'assignments_files/' . $fileName;
        }

        $assignment->save();
        return response()->json(['message' => 'Assignment created successfully.', 'assignment' => $assignment], 201);
    }

    public function show(Assignment $assignment)
    {
        return response()->json($assignment);
    }

    public function update(AssignmentRequest $request, Assignment $assignment)
    {
        $assignment->fill($request->validated());

        if ($request->hasFile('file')) {
            if ($assignment->file) {
                Storage::delete('public/' . $assignment->file);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/assignments_files', $fileName);
            $assignment->file = 'assignments_files/' . $fileName;
        }

        $assignment->save();
        return response()->json(['message' => 'Assignment updated successfully.', 'assignment' => $assignment]);
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return response()->json(['message' => 'Assignment deleted successfully.']);
    }
}
