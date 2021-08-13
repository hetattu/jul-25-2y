<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;

class PostApiController extends ApiController
{
    public function index()
    {
        $user_id = Auth::id();
        return [
            'posts' => Post::where('user_id', $user_id)->get(),
            'tags' => Tag::orderBy('order', 'asc')->get(),
        ];
    }

    public function store(Request $request)
    {
        Post::create([
            'user_id' => $request->user()->id,
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            'tags' => $request->input('tags'),
        ]);
    }

    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        $comments = Comment::where('post_id', $id)->orderBy('created_at', 'desc')->get();
        $tags = Tag::orderBy('order', 'asc')->get();

        return [
            'post' => $post,
            'comments' => $comments,
            'tags' => $tags,
        ];
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
