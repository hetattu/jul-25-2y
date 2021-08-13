<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentApiController extends ApiController
{
    public function store(Request $request)
    {
        $store_data = Comment::create([
            'post_id' => $request->input('post_id'),
            'user_id' => $request->user()->id,
            'body' => $request->input('body'),
        ]);
        return $store_data;
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->fill($request->all())->save();
    }

    public function destroy($id)
    {
        return Comment::where('id', $id)->delete();
    }
}
