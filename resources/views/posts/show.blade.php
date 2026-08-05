@extends('app')

@section('content')
    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Posts
    </a>

    <div class="card border-0 shadow-sm" style="border-radius: 16px; max-width: 800px;">
        @if ($post->img)
            <img src="{{ Storage::url($post->img) }}" alt="{{ $post->title }}" class="card-img-top"
                 style="max-height: 520px; object-fit: fill; border-radius: 16px 16px 0 0;">
        @endif
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h2 class="mb-0">{{ $post->title }}</h2>
                <span class="status-pill {{ $post->status === 'live' ? 'bg-success' : 'bg-secondary' }} badge">
                    {{ ucfirst($post->status) }}
                </span>
            </div>
            <p class="text-muted small mb-4">Published {{ $post->created_at->diffForHumans() }}</p>
            <div>{{ $post->description }}</div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary"
                   style="background-color: var(--color-primary); border-color: var(--color-primary);">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection