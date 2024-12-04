<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Answer;
use App\Http\Requests\AnswerRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AnswerController extends Controller
{
    public function index($assignmentId)
    {
        $answers = Answer::where('assignment_id', $assignmentId)->get();

        return response()->json($answers);
    }

    public function store(AnswerRequest $request)
    {
        $validatedData = $request->validated();

        // Check if the user has already uploaded an answer for this assignment
        $existingAnswer = Answer::where('user_id', auth()->user()->id)
            ->where('assignment_id', $validatedData['assignment_id'])
            ->exists();

        if ($existingAnswer) {
            return response()->json([
                'message' => 'You have already uploaded an answer for this assignment.'
            ], 400);
        }

        $answer = new Answer([
            'assignment_id' => $validatedData['assignment_id'],
            'user_id' => auth()->id(),
            'uploaded_at' => now(),
        ]);

        // check for the file upload if present
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/answer_files', $fileName);
            $answer->file_path = 'answer_files/' . $fileName;
        }

        $answer->save();

        return response()->json([
            'message' => 'Answer uploaded successfully',
            'answer' => $answer
        ], 201);
    }

    public function show($id)
    {
        $answer = Answer::findOrFail($id);
        return response()->json($answer);
    }


    

    public function destroy($assignmentId, $answerId)
    {
        $answer = Answer::where('id', $answerId)->where('assignment_id', $assignmentId)->first();

        if ($answer) {
            $answer->delete();
            return response()->json([
                'message' => 'Answer deleted successfully.'
            ]);
        } else {
            return response()->json([
                'message' => 'Answer not found.'
            ], 404);
        }
    }
}
