@extends('app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        .avatar-circle {
            width: 40px; height: 40px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.85rem;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: #fff;
        }
        .message-modal .modal-body { max-height: 400px; overflow-y: auto; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 1060; }
        #usersTable td { vertical-align: middle; }
        .btn-icon { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#usersTable')) {
                $('#usersTable').DataTable().destroy();
            }

            $('#usersTable').DataTable({
                responsive: true,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: -1 }],
            });

            $(document).on('click', '.message-btn', function() {
                $('#recipient-id').val($(this).data('id'));
                $('#recipient-name').text($(this).data('name'));
                $('#message-text').val('');
                $('#char-count').text('0');
                $('#messageModal').modal('show');
            });

            $('#message-text').on('input', function() {
                $('#char-count').text($(this).val().length);
            });

            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                const userId = $('#recipient-id').val();
                const message = $('#message-text').val().trim();
                const $btn = $('#send-btn');
                const originalHtml = $btn.html();

                if (!message) return;

                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Sending...');

                $.ajax({
                    url: `/users/${userId}/message`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        message: message
                    },
                    success: function(response) {
                        $('#messageModal').modal('hide');
                        showToast(response.message, 'success');
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.errors?.message?.[0] || 'Something went wrong.';
                        showToast(msg, 'danger');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            function showToast(text, type) {
                const $toast = $('#liveToast');
                $toast.removeClass('bg-success bg-danger').addClass('bg-' + type);
                $('#toast-message').text(text);
                new bootstrap.Toast($toast[0]).show();
            }
        });
    </script>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">All Users</h2>
            <p class="text-muted small mb-0">Browse users and send messages</p>
        </div>
        <span class="badge bg-primary rounded-pill">{{ $users->count() }} users</span>
    </div>

    <div class="card posts-card p-3">
        <table id="usersTable" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    {{-- <th>Email</th> --}}
                    <th>Joined</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @php
                        $initials = collect(explode(' ', $user->name))
                            ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                            ->join('');
                    @endphp
                    <tr>
                        <td>
                            <div class="avatar-circle">{{ $initials }}</div>
                        </td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        {{-- <td class="text-muted">{{ $user->email }}</td> --}}
                        <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-primary message-btn d-inline-flex align-items-center gap-1"
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                <i class="bi bi-chat-dots-fill"></i> Message
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Message Modal --}}
    <div class="modal fade message-modal" id="messageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-chat-dots-fill me-2 text-primary"></i>
                        Message <span id="recipient-name" class="fw-bold"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="messageForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="recipient-id">
                        <div class="mb-3">
                            <label for="message-text" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message-text" rows="4" 
                                      placeholder="Type your message here..." required maxlength="2000"></textarea>
                            <div class="form-text text-end"><span id="char-count">0</span> / 2000</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="send-btn">
                            <i class="bi bi-send-fill me-1"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div class="toast-container">
        <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span id="toast-message">Message sent!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endsection