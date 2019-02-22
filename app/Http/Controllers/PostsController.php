<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Events\PostWasUpdated;
use App\Http\Requests\PostRequest;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index()
    {
        $posts = Post::latest()->get();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        $post = auth()->user()->posts()->create($request->only('title', 'slug', 'preview'));
    }

    public function edit(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }
    }

    public function update(Post $post, Request $request)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required'
        ]);

        $post->update([
            'title' => $request->title
        ]);

        event(new PostWasUpdated($post));

        return redirect()->route('posts.edit', $post);
    }
}
