<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'required|image|max:2048', // Adjust the max file size as needed
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|boolean',
            'author_id' => 'required|exists:users,id',
            'delivery_mode' => 'required|in:present,online',
            'max_students' => 'nullable|integer|min:0',
            'previous_price' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/|gte:price',
        ];

        

        return $rules;
    }
}
