<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user'); // Get the user ID from the route

        return [
            'id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $userId, // Exclude current user's email from the uniqueness check
            'role' => 'required|in:admin,student',
            'status' => 'required|boolean',
            'member' => 'required|boolean',
        ];
    }
}
