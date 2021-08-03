<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        return Post::where('user_id', $user_id)->get();
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

    public function show($id)
    {
        return Post::where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        $post = Post::where('id', $id)->first();
        $post->fill($request->all())->save();
        return json_encode($post);
    }

    public function destroy($id)
    {
        Post::where('id', $id)->delete();
    }
}
