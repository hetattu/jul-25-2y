<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        return [
            [
                'id' => 1,
                'subject' => 'api_subject1',
                'body' => 'api_body1',
            ]
        ];
    }

    public function store() {}

    public function show($id) {}

    public function edit($id) {}

    public function update($id) {}

    public function destroy($id) {}
}
