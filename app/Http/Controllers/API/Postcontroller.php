<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Postcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();

        return response()->json([
            'status' => true,
            'message' => 'all posts data',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateruser = Validator::make($request->all(), [
            'title' => 'required|string',
            'discription' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validateruser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateruser->errors()->all(),
            ], 401);
        }

        #post creation logic
        $post = Post::create([
            'title' => $request->title,
            'discription' => $request->discription,
            'image' => $request->image->store('posts', 'public'),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::find($id);

        return response()->json([
            'status' => true,
            'message' => 'Post data',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateruser = Validator::make($request->all(), [
            'title' => 'required|string',
            'discription' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validateruser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateruser->errors()->all(),
            ], 401);
        }

        #post update logic
        $post = Post::find($id);
        $post->update([
            'title' => $request->title,
            'discription' => $request->discription,
            'image' => $request->image->store('posts', 'public'),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        // add logic to delete the image from storage if needed
        $post = Post::find($id);
        if ($post && $post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $data['post'] = Post::find($id);
        $data['post']->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully',
        ], 200);
    }
}
