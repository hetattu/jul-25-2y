<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Comment;
use App\Http\Resources\Comment as CommentResource;

class CommentApiController extends ApiController
{
    public function store(Request $request)
    {
        try {
            $comment = Comment::create($request->all() + ['user_id' => $request->user()->id]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->respondNotFound();
        }

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        try {
            Comment::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            $this->respondNotFound();
        }

        return $this->respondObjectDeleted($id);
    }
}
