<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        Post::create([
            'user_id' => $request->user()->id,
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            'tags' => json_encode([]),
        ]);
    }

    public function show($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}