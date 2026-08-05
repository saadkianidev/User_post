<style>
    .site-header {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary)) !important;
        color: var(--color-bg) !important;
    }
    .site-header .btn-outline-secondary {
        color: var(--color-bg) !important;
        border-color: var(--color-bg) !important;
    }
    .site-header .btn-outline-secondary:hover {
        background-color: rgba(255, 255, 255, .15) !important;
    }
    .notif-dropdown { width: 340px; max-height: 400px; }
    .notif-dropdown .dropdown-item { white-space: normal; }
    .notif-dropdown .notif-time { font-size: .7rem; color: #6b7280; }
    .notif-dropdown .notif-text { font-size: .85rem; line-height: 1.3; }
    .notif-dropdown .notif-sender { font-weight: 600; font-size: .8rem; }
    #notif-badge:empty { display: none !important; }
    @keyframes notif-pulse {
        0% { background-color: rgba(220, 53, 69, 1); }
        50% { background-color: rgba(220, 53, 69, 0.6); }
        100% { background-color: rgba(220, 53, 69, 1); }
    }
    .notif-new { animation: notif-pulse 1.5s infinite; }
</style>

<header class="site-header border-bottom">
    <nav class="navbar navbar-expand-lg px-3">
        <span class="navbar-brand fw-bold" style="color: var(--color-bg);">Engineer Yourself</span>

        <div class="ms-auto d-flex align-items-center gap-2">
            {{-- Notification Bell --}}
            <div class="dropdown">
                <button class="btn btn-outline-secondary position-relative" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false" id="notif-toggle">
                    <i class="bi bi-bell-fill"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          id="notif-badge" style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">
                        {{ $unreadCount }}
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end notif-dropdown p-0" aria-labelledby="notif-toggle">
                    <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 bg-light">
                        <span class="fw-semibold small">Notifications</span>
                        @if($unreadCount > 0)
                            <button class="btn btn-link btn-sm text-decoration-none p-0" onclick="markAllRead()">
                                Mark all read
                            </button>
                        @endif
                    </li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <div id="notif-list" style="max-height: 320px; overflow-y: auto;">
                        @forelse($recentNotifs as $notif)
                            <a href="#" class="dropdown-item px-3 py-2 border-bottom">
                                <div class="notif-sender text-primary">{{ $notif->data['sender_name'] }}</div>
                                <div class="notif-text text-muted">{{ $notif->data['content'] }}</div>
                                <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                            </a>
                        @empty
                            <li class="dropdown-item text-muted text-center py-3" id="no-notifs">No new notifications</li>
                        @endforelse
                    </div>
                </ul>
            </div>

            {{-- User Dropdown --}}
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('settings') }}">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

{{-- Echo + Reverb via CDN (NO NPM) --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

<script>
    // Initialize Echo with Reverb
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: '{{ config("reverb.apps.0.key", "local") }}',
        wsHost: '{{ config("reverb.apps.0.options.host", "localhost") }}',
        wsPort: {{ config("reverb.apps.0.options.port", 8080) }},
        wssPort: {{ config("reverb.apps.0.options.port", 8080) }},
        forceTLS: {{ config("reverb.apps.0.options.scheme", "http") === 'https' ? 'true' : 'false' }},
        enabledTransports: ['ws', 'wss'],
    });

    const currentUserId = {{ auth()->id() }};

    // Listen for real-time notifications
    Echo.private('App.Models.User.' + currentUserId)
        .notification((notification) => {
            // 1. Update badge
            const badge = document.getElementById('notif-badge');
            let count = parseInt(badge.innerText) || 0;
            badge.innerText = count + 1;
            badge.style.display = 'inline-block';
            badge.classList.add('notif-new');

            // 2. Remove "no notifications" placeholder
            const noNotifs = document.getElementById('no-notifs');
            if (noNotifs) noNotifs.remove();

            // 3. Prepend new notification to dropdown
            const list = document.getElementById('notif-list');
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'dropdown-item px-3 py-2 border-bottom notif-new';
            item.innerHTML = `
                <div class="notif-sender text-primary">${notification.data.sender_name}</div>
                <div class="notif-text text-muted">${notification.data.content}</div>
                <div class="notif-time">Just now</div>
            `;
            list.insertBefore(item, list.firstChild);

            // 4. Browser notification (optional)
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('New message from ' + notification.data.sender_name, {
                    body: notification.data.content,
                    icon: '/favicon.ico'
                });
            }
        });

    // Mark all as read
    function markAllRead() {
        fetch('{{ route("notifications.read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('notif-badge').style.display = 'none';
                document.getElementById('notif-badge').classList.remove('notif-new');
                document.getElementById('notif-list').innerHTML = 
                    '<li class="dropdown-item text-muted text-center py-3" id="no-notifs">No new notifications</li>';
            }
        });
    }

    // Request browser notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
</script>