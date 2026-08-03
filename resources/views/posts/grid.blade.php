@extends('app')

@push('styles')
<style>
    .ig-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; }
    .ig-tile { position: relative; aspect-ratio: 1/1; overflow: hidden; background: #f1f2f6; }
    .ig-tile img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .ig-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,.55); color: #fff;
        display: flex; align-items: center; justify-content: center; text-align: center;
        opacity: 0; transition: opacity .2s; padding: 12px; font-weight: 600;
    }
    .ig-tile:hover .ig-overlay { opacity: 1; }
    @media (max-width: 768px) { .ig-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">My Posts</h2>
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list"></i> List View
        </a>
    </div>

    @if ($posts->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-image fs-1 d-block mb-2"></i>
            No posts yet.
        </div>
    @else
        <div class="ig-grid">
            @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="ig-tile">
                    @if ($post->img)
                        <img src="{{ Storage::url($post->img) }}" alt="{{ $post->title }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    @endif
                    <div class="ig-overlay">{{ $post->title }}</div>
                </a>
            @endforeach
        </div>
    @endif
@endsection