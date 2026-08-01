<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()->latest()->get();

        return view('posts.index', compact('posts'));
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

        $imageUrl = null;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $storage = Firebase::storage()->getBucket();

            $filename = 'posts/'.uniqid().'_'.$file->getClientOriginalName();

            $object = $storage->upload(
                fopen($file->getRealPath(), 'r'),
                ['name' => $filename]
            );

            $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);
            $imageUrl = "https://storage.googleapis.com/{$storage->name()}/{$filename}";
        }

        auth()->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'img' => $imageUrl,
            'status' => $validated['status'],
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $this->authorize('view', $post);

        return view('posts.index', compact('post'));
    }
}
