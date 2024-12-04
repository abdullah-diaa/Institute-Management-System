<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            'user_id' => 'required|exists:users,id',
            'location' => 'required|string|max:30', // Location max 30 characters
            'phone_number' => 'required|digits:11', // Exactly 11 digits (or use digits_between if the length varies)
            'date_of_birth' => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max file size 2MB
        ];

    }
}

