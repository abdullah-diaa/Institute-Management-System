<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Subscription;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all(); 
        $userId = auth()->id(); 

        // Fetch subscriptions for the user
        $subscriptions = Subscription::where('user_id', $userId)
            ->get()
            ->keyBy('course_id'); // Use course_id as the key for easy lookup

        return response()->json([
            'courses' => $courses,
            'subscriptions' => $subscriptions
        ]);
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/course_images', $imageName);
            $data['image'] = 'course_images/' . $imageName;
        }

        $course = Course::create($data);

        return response()->json([
            'message' => 'Course created successfully.',
            'course' => $course
        ], 201);
    }

    public function show(Course $course)
    {
        $userId = auth()->id(); 

        $subscription = Subscription::where('course_id', $course->id)
            ->where('user_id', $userId)
            ->first(); // Get the first matching record or null if none exists

        return response()->json([
            'course' => $course,
            'subscription' => $subscription
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = $request->validated();

        // check for image upload if a new image is provided
        if ($request->hasFile('image')) {
          
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/course_images', $imageName);
            $data['image'] = 'course_images/' . $imageName;
        }

        $course->update($data);

        return response()->json([
            'message' => 'Course updated successfully.',
            'course' => $course
        ]);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully.'
        ]);
    }
}
