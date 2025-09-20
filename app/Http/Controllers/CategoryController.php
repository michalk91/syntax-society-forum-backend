<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // only admin can create category
    public function store(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:categories|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->only('name', 'description'));

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    // categories list
    public function index()
    {
        return response()->json(Category::all());
    }

    // single category
    public function show(Category $category)
    {
        return response()->json($category->load('posts'));
    }
}
