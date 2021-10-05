<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function findAll()
    {
        return response( Post::all(), 200);
    }

    public function findById($id) {

    }

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response($post, 201);
    }

    public function update(Request $request, $id) {

    }

    public function destroy() {

    }
}
