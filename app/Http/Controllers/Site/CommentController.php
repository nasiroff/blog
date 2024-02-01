<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Post $post)
    {
        $payload = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $post->comments()->save(
            new Comment($payload)
        );
        return redirect()->back(Response::HTTP_CREATED);
    }
}
