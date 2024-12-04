<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'course'])->orderBy('created_at', 'desc')->get();
        return response()->json($subscriptions, 200);
    }

    // Get successful subscriptions
    public function successfulSubscriptions()
    {
        $successfulSubscriptions = Subscription::where('request_status', 'successful')
            ->with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($successfulSubscriptions, 200);
    }

    // Get failed subscriptions
    public function failedSubscriptions()
    {
        $failedSubscriptions = Subscription::where('request_status', 'failed')
            ->with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($failedSubscriptions, 200);
    }

    // Create a subscription for spesific course
    public function store(StoreSubscriptionRequest $request, Course $course)
    {
        $validated = $request->validated();
        $validated['course_id'] = $course->id;
        $validated['user_id'] = auth()->id();
        $validated['request_status'] = 'pending';

        $subscription = Subscription::create($validated);

        return response()->json(['message' => 'Subscription created successfully.', 'subscription' => $subscription], 201);
    }

    public function show($id)
    {
        $subscription = Subscription::with(['user', 'course'])->findOrFail($id);
        return response()->json($subscription, 200);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $validated = $request->validated();

        if ($validated['request_status'] === 'successful') {
            $validated['approved_by'] = auth()->id();
            $user = $subscription->user;
            $course = $subscription->course;
            $user->courses()->syncWithoutDetaching([$course->id]);
        } else {
            unset($validated['approved_by']);
            if (in_array($validated['request_status'], ['pending', 'failed'])) {
                $user = $subscription->user;
                $course = $subscription->course;
                $user->courses()->detach($course);
            }
        }

        $subscription->update($validated);

        return response()->json(['message' => 'Subscription updated successfully.', 'subscription' => $subscription], 200);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->json(['message' => 'Subscription deleted successfully.'], 200);
    }
}
