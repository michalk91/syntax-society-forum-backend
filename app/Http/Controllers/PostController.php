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

        $user = $request->user();

        $post = Post::create([
            'user_id' => $user->id,
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'is_approved' => false,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    public function index()
    {
        $user = auth()->user();

        if ($user && ($user->isAdmin() || $user->isModerator())) {
            $posts = Post::with('user', 'category')->get();
        } else {
            $posts = Post::approved()->with('user', 'category')->get();
        }

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function approve(Post $post)
    {
        $user = auth()->user();

        if (!($user->isAdmin() || $user->isModerator())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->is_approved = true;
        $post->save();

        return response()->json([
            'message' => 'Post approved',
            'post' => $post
        ]);
    }
}
