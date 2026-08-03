@extends('app')

@push('styles')
    <style>
        .stat-card {
            border: none; border-radius: 16px; padding: 1.25rem;
            background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.05);
            display: flex; align-items: center; gap: 14px;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; flex-shrink: 0;
        }
        .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1.1; }
        .stat-label { font-size: .8rem; color: #6b7280; }

        .posts-card { border: none; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,.06); }
        #postsTable thead th { border-top: none; color: #6b7280; font-size: .8rem; text-transform: uppercase; letter-spacing: .03em; }
        #postsTable tbody tr { vertical-align: middle; }
        .post-thumb { width: 46px; height: 46px; object-fit: cover; border-radius: 10px; }
        .post-thumb-placeholder {
            width: 46px; height: 46px; border-radius: 10px; background: #f1f2f6;
            display: flex; align-items: center; justify-content: center; color: #c1c4cc;
        }
        .status-pill { border-radius: 999px; padding: .3em .75em; font-weight: 500; font-size: .75rem; }
        .status-pill.live { background: color-mix(in srgb, var(--color-secondary) 15%, white); color: var(--color-secondary); }
        .status-pill.draft { background: #f1f2f6; color: #6b7280; }
        .btn-icon { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
        .table-toolbar .btn { border-radius: 10px; }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 table-toolbar">
        <div>
            <h2 class="mb-0">My Posts</h2>
            <p class="text-muted small mb-0">Manage and browse everything you've published</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('posts.grid') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-grid-3x3-gap-fill"></i> Grid View
            </a>
            <a href="{{ route('posts.create') }}" class="btn btn-primary d-flex align-items-center gap-2"
               style="background-color: var(--color-primary); border-color: var(--color-primary);">
                <i class="bi bi-plus-lg"></i> New Post
            </a>
        </div>
    </div>

   

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card posts-card p-3">
        <table id="postsTable" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($post->img)
                                    <img src="{{ $post->img }}" alt="{{ $post->title }}" class="post-thumb">
                                @else
                                    <div class="post-thumb-placeholder"><i class="bi bi-image"></i></div>
                                @endif
                                <a href="{{ route('posts.show', $post) }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </div>
                        </td>
                        <td class="text-muted">{{ Str::limit(strip_tags($post->description), 80) }}</td>
                        <td>
                            <span class="status-pill {{ $post->status === 'live' ? 'live' : 'draft' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $post->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#postsTable')) {
                $('#postsTable').DataTable().destroy();
            }
            $('#postsTable').DataTable({
                responsive: true,
                order: [[3, 'desc']],
                columnDefs: [{ orderable: false, targets: -1 }],
            });
        });
    </script>
@endpush