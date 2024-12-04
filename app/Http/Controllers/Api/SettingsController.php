<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\User;

class SettingsController extends Controller
{
    // Get authenticated user's settings
    public function index()
    {
        $user = Auth::user();
        return response()->json(['user' => $user]);
    }

    // Update user's name
    public function updateName(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($user->last_name_update && $user->last_name_update->diffInMonths(now()) < 1) {
            return response()->json(['error' => 'You can only change your name once every month.'], 403);
        }

        $user->update([
            'name' => $request->name,
            'last_name_update' => now(),
        ]);

        return response()->json(['message' => 'Name updated successfully.']);
    }

    // Update user's phone number
    public function updatePhoneNumber(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone_number' => 'required|string|max:15',
        ]);

        $profile = $user->profile;

        // you can see that the user can't update his phone number unless the last update was 1 month from now
        if ($profile->last_phone_update && $profile->last_phone_update->diffInMonths(now()) < 1) {
            return response()->json(['error' => 'You can only change your phone number once every month.'], 403);
        }

        $profile->update([
            'phone_number' => $request->phone_number,
            'last_phone_update' => now(),
        ]);

        return response()->json(['message' => 'Phone number updated successfully.']);
    }

    // Update location and date of birth
    public function updateProfileInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'location' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);

        $user->profile->update([
            'location' => $request->location,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return response()->json(['message' => 'Profile information updated successfully.']);
    }

    // Update gender
    public function updateGender(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'gender' => 'required|in:male,female',
        ]);

        $user->update(['gender' => $request->gender]);

        return response()->json(['message' => 'Gender updated successfully.']);
    }

    // Update password
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password is incorrect.'], 403);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    // Update profile picture
    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->profile->profile_picture) {
            Storage::delete($user->profile->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        $user->profile->update(['profile_picture' => $path]);

        return response()->json(['message' => 'Profile picture updated successfully.', 'path' => $path]);
    }
}
