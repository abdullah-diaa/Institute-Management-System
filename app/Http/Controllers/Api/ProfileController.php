<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;

class ProfileController extends Controller
{

    //this controller for showing the data about the user's profile
    public function index()
    {
        $profiles = Profile::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($profiles);
    }

    public function store(StoreProfileRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $profile = Profile::create($data);

        return response()->json(['message' => 'Profile created successfully.', 'profile' => $profile], 201);
    }

    public function show(Profile $profile)
    {
        return response()->json($profile);
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully.']);
    }
}
