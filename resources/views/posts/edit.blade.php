@extends('app')

@section('content')
    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Posts
    </a>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; max-width: 700px;">
        <h3 class="mb-4">Edit Post</h3>

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $post->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="form-control @error('description') is-invalid @enderror"
                          required>{{ old('description', $post->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="live" {{ old('status', $post->status) === 'live' ? 'selected' : '' }}>Live</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Image</label>
                @if ($post->img)
                    <div class="mb-2">
                        <img src="{{ Storage::url($post->img) }}" alt="{{ $post->title }}"
                             class="img-thumbnail" style="max-width: 150px;">
                    </div>
                @endif
                <input type="file" name="img" id="img"
                       class="form-control @error('img') is-invalid @enderror" accept="image/*">
                <div class="form-text">Leave empty to keep the current image.</div>
                @error('img')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"
                        style="background-color: var(--color-primary); border-color: var(--color-primary);">
                    Save Changes
                </button>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection