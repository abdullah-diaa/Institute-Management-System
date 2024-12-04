<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\YoutubeVideo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreYoutubeVideoRequest;
use App\Http\Requests\UpdateYoutubeVideoRequest;

class YoutubeVideoController extends Controller
{
    
    //  Display a listing of the YouTube videos.
     
    public function index()
    {
        $videos = YoutubeVideo::orderBy('created_at', 'desc')->paginate(15); // Paginate videos (15 per page)
        return response()->json($videos, 200); // Return JSON response
    }

   
    
    public function store(StoreYoutubeVideoRequest $request)
    {
        $validatedData = $request->validated();
        $video = YoutubeVideo::create($validatedData);

        return response()->json([
            'message' => 'Video added successfully.',
            'data' => $video,
        ], 201); 
    }

   


    /**
     * Update the specified YouTube video in storage.
     */

   

    public function update(UpdateYoutubeVideoRequest $request, $id)
    {
        $video = YoutubeVideo::findOrFail($id);

        $validatedData = $request->validated();
        $video->update($validatedData);

        return response()->json([
            'message' => 'Video updated successfully.',
            'data' => $video,
        ], 200); // Return success response
    }

   
    public function destroy($id)
    {
        $video = YoutubeVideo::findOrFail($id);
        $video->delete();

        return response()->json([
            'message' => 'Video deleted successfully.',
        ], 200);
    }











}
