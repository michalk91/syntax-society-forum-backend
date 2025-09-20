<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $category_id = $request->input('category_id');
        $title = $request->input('title');
        $content = $request->input('content');

        $post = Post::create([
            'user_id' => $user->id,
            'category_id' => $category_id,
            'title' => $title,
            'content' => $content,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }
}

