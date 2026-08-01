@extends('app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create New Post</h2>
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">← Back to My Posts</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Image</label>
            <input type="file" name="img" id="img" accept="image/*"
                class="form-control @error('img') is-invalid @enderror">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="live" {{ old('status') === 'live' ? 'selected' : '' }}>Live</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Publish Post</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#description',
            height: 400,
            menubar: false,
            plugins: 'lists link image code table charmap preview searchreplace visualblocks fullscreen media wordcount',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | ' +
                'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    </script>
@endsection
