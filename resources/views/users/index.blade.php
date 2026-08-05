@extends('app')

@section('title', 'All Users')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .message-modal .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
        /* Toast positioning */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>All Users</h4>
        <span class="text-muted" id="user-count">Loading...</span>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover" id="users-table" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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
                    <input type="hidden" id="recipient-id" name="user_id">
                    <div class="mb-3">
                        <label for="message-text" class="form-label">Your Message</label>
                        <textarea 
                            class="form-control" 
                            id="message-text" 
                            name="message" 
                            rows="4" 
                            placeholder="Type your message here..."
                            required
                            maxlength="2000"
                        ></textarea>
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

{{-- Toast Notification --}}
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

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route("users.index") }}',
                columns: [
                    { data: 'avatar', name: 'avatar', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
                ],
                language: {
                    emptyTable: "No users found",
                    zeroRecords: "No matching users found"
                },
                initComplete: function(settings, json) {
                    $('#user-count').text(table.rows().count() + ' users');
                }
            });

            // Open message modal
            $(document).on('click', '.message-btn', function() {
                const userId = $(this).data('id');
                const userName = $(this).data('name');
                
                $('#recipient-id').val(userId);
                $('#recipient-name').text(userName);
                $('#message-text').val('').trigger('input');
                $('#messageModal').modal('show');
            });

            // Character counter
            $('#message-text').on('input', function() {
                $('#char-count').text($(this).val().length);
            });

            // Send message
            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                
                const userId = $('#recipient-id').val();
                const message = $('#message-text').val().trim();
                const $btn = $('#send-btn');
                const originalText = $btn.html();

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
                        let errorMsg = 'Something went wrong.';
                        if (xhr.responseJSON?.errors?.message) {
                            errorMsg = xhr.responseJSON.errors.message[0];
                        }
                        showToast(errorMsg, 'danger');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            function showToast(message, type) {
                const $toast = $('#liveToast');
                $toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
                $('#toast-message').text(message);
                const toast = new bootstrap.Toast($toast[0]);
                toast.show();
            }
        });
    </script>
@endpush