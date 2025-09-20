<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ReplyController extends Controller
{
    public function store(Request $request, Post $post)
    {
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $reply = $post->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Reply added successfully',
            'reply' => $reply
        ], 201);
    }


    public function index(Post $post)
    {
        return response()->json($post->replies()->with('user')->get());
    }
}
