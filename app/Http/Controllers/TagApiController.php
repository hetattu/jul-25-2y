<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Tag;
use App\Http\Resources\Tag as TagResource;

class TagApiController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TagResource::collection(Tag::orderBy('order', 'asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $tag = Tag::create($request->all());
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->fill($request->all())->save();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Tag::findOrFail($id)->delete();
        } catch (ModalNotFoundException $e) {
            return $this->respondNotFound();
        }

        return $this->respondObjectDeleted($id);
    }
}
