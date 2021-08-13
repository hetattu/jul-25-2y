<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\Tag as TagResource;

class PostApiController extends ApiController
{
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        $tags = Tag::orderBy('order', 'asc')->get();
        return [
            'posts' => PostResource::collection($posts),
            'tags' => TagResource::collection($tags),
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
            'post' => new PostResource($post),
            'comments' => CommentResource::collection($comments),
            'tags' => TagResource::collection($tags),
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
