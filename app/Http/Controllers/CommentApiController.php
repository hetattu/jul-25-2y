<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentApiController extends Controller
{
    public function store(Request $request)
    {
        Comment::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->fill($request->all())->save();
    }

    public function destroy($id)
    {
        Comment::where('id', $id)->delete();
    }
}
