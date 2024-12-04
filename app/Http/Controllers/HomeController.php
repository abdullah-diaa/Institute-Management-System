<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Display the latest courses and posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $courses = Course::latest()->take(3)->get(); // Here to Fetch the latest 3 courses
        $posts = Post::latest()->take(3)->get(); // and here just to show the  the latest 3 posts 

        return response()->json([
            'success' => true,
            'data' => [
                'courses' => $courses,
                'posts' => $posts,
            ],
        ], 200); 
    }
}
