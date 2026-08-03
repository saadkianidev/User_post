<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()->latest()->get();

        $stats = [
            'total' => $posts->count(),
            'live' => $posts->where('status', 'live')->count(),
            'draft' => $posts->where('status', '!=', 'live')->count(),
            'images' => $posts->whereNotNull('img')->where('img', '!=', '')->count(),
        ];

        return view('posts.index', compact('posts', 'stats'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'img' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'in:draft,live'],
        ]);

        $imagePath = null;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = uniqid().'_'.$file->getClientOriginalName();

            $file->storeAs('posts', $filename, 'public');

            $imagePath = 'posts/'.$filename;
        }

        auth()->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'img' => $imagePath,
            'status' => $validated['status'],
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $this->authorize('view', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'img' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'in:draft,live'],
        ]);

        $imagePath = $post->img;

        if ($request->hasFile('img')) {
            if ($post->img) {
                \Storage::disk('public')->delete($post->img);
            }

            $file = $request->file('img');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            $file->storeAs('posts', $filename, 'public');
            $imagePath = 'posts/'.$filename;
        }

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'img' => $imagePath,
            'status' => $validated['status'],
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return view('posts.show', compact('post'));
    }

    public function grid()
    {
        $posts = auth()->user()->posts()->latest()->get();

        return view('posts.grid', compact('posts'));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->img) {
            \Storage::disk('public')->delete($post->img);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    public function allPosts()
    {
        $posts = Post::with('user')->latest()->get();

        return view('posts.all', compact('posts'));
    }
}
