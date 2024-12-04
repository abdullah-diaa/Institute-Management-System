<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    // Enroll a user in courses
    public function store(Request $request, User $user)
    {
        $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $user->courses()->attach($request->course_ids);

        return response()->json(['message' => 'Enrollment created successfully!'], 201);
    }

    // Edit enrollments for a user (Get current enrollments)
    public function edit(User $user)
    {
        $courses = Course::all(); // All available courses
        $userCourses = $user->courses->pluck('id'); // User's enrolled courses

        return response()->json([
            'user' => $user,
            'courses' => $courses,
            'user_courses' => $userCourses,
        ]);
    }

    // Update a user's enrollments
    public function update(Request $request, User $user)
    {
        $request->validate([
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $user->courses()->sync($request->course_ids);

        return response()->json(['message' => 'Enrollment updated successfully!'], 200);
    }
}
