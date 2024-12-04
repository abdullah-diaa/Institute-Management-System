<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return response()->json($posts, 200);
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        $post->incrementViews();
        return response()->json($post, 200);
    }

    // Increment view count for a post so the viewer can see how many views on the post
    public function incrementView($id)
    {
        $post = Post::findOrFail($id);
        $post->incrementViews();
        return response()->json(['success' => true], 200);
    }

    public function store(PostRequest $request)
    {
        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/posts_images', $imageName);
            $post->image = 'posts_images/' . $imageName;
        }

        $post->save();
        return response()->json(['message' => 'Post created successfully.', 'post' => $post], 201);
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/posts_images', $imageName);
            $post->image = 'posts_images/' . $imageName;
        }

        $post->save();
        return response()->json(['message' => 'Post updated successfully.', 'post' => $post], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully.'], 200);
    }
}
