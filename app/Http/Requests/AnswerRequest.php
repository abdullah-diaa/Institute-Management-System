<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'assignment_id' => 'required|exists:assignments,id',
            'user_id' => 'nullable|exists:users,id',
         
        'file_path' => 'required|file|mimes:pdf,docx,jpeg,png,gif', 
    
        ];
    }
}
