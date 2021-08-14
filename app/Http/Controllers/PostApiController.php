<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try {
            $post = Post::create($request->all() + ['user_id' => $request->user()->id]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return new PostResource($post);
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            $comments = $post->comments;
            $tags = Tag::orderBy('order', 'asc')->get();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return [
            'post' => new PostResource($post),
            'comments' => CommentResource::collection($comments),
            'tags' => TagResource::collection($tags),
        ];
    }

    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->fill($request->all())->save();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return new PostResource($post);
    }

    public function destroy($id)
    {
        try {
            Post::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return $this->respondObjectDeleted($id);
    }
}
