<?php

namespace App\Http\Controllers\Api;

use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;

class PlaylistController extends Controller
{

    // Dear viewer 
    // you can see in this structure that playlist is already engaged with youtube-video with relationship
    public function index()
    {
       
        $playlists = Playlist::orderBy('created_at', 'desc')->paginate(10);

        return response()->json($playlists);
    }

    public function store(StorePlaylistRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/playlist_images', $imageName);
            $data['image'] = 'playlist_images/' . $imageName;
        }

        $playlist = Playlist::create($data);

        if ($videoIds = $request->input('video_ids')) {
            $playlist->videos()->attach(json_decode($videoIds, true));
        }

        return response()->json(['message' => 'Playlist created successfully!', 'playlist' => $playlist], 201);
    }

    public function show($id)
    {
        $playlist = Playlist::with(['videos'])->findOrFail($id);
        $videos = $playlist->videos()->paginate(10);

        return response()->json(['playlist' => $playlist, 'videos' => $videos]);
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/playlist_images', $imageName);
            $data['image'] = 'playlist_images/' . $imageName;
        }

        $playlist->update($data);

        if ($videoIds = $request->input('video_ids')) {
            $playlist->videos()->sync(json_decode($videoIds, true));
        } else {
            $playlist->videos()->detach();
        }

        return response()->json(['message' => 'Playlist updated successfully!', 'playlist' => $playlist]);
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->delete();

        return response()->json(['message' => 'Playlist deleted successfully.']);
    }
}
