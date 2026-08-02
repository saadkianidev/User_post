<head>
    ...
    <script>
        (function () {
            const saved = JSON.parse(localStorage.getItem('siteTheme') || '{}');
            const root = document.documentElement;
            if (saved.primary) root.style.setProperty('--color-primary', saved.primary);
            if (saved.secondary) root.style.setProperty('--color-secondary', saved.secondary);
            if (saved.bg) root.style.setProperty('--color-bg', saved.bg);
        })();
    </script>
    <style>
        :root {
            --color-primary: #4f46e5;
            --color-secondary: #22c55e;
            --color-bg: #ffffff;
        }
    </style>
    @stack('styles')
</head>

<aside class="site-sidebar text-white p-3"
    style="width: 220px; min-height: 100vh; background-color: var(--color-primary); color: var(--color-bg) !important;">
    <nav class="nav flex-column gap-2">
        <a href="{{ route('posts.index') }}"
            class="nav-link text-white {{ request()->routeIs('posts.index') ? '' : '' }}">
            My Posts
        </a>
        <a href="{{ route('posts.create') }}"
            class="nav-link text-white {{ request()->routeIs('posts.create') ? '' : '' }}">
            Create New Post
        </a>
    </nav>
</aside>