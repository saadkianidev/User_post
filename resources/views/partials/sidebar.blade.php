<style>
    :root {
        --color-primary: #4f46e5;
        --color-secondary: #22c55e;
        --color-bg: #ffffff;
    }

    .nav-link{
        background-color: var(--color-secondary) !important;
        color: var(--color-bg) !important;
    }

    .site-sidebar {}
</style>

<aside class="site-sidebar text-white p-3" style="width: 220px; min-height: 100vh; background-color: var(--color-primary); color: var(--color-bg) !important;">
    <nav class="nav flex-column gap-2">
        <a href="{{ route('posts.index') }}"
            class="nav-link text-white {{ request()->routeIs('posts.index') ? 'active bg-secondary rounded' : '' }}">
            My Posts
        </a>
        <a href="{{ route('posts.create') }}"
            class="nav-link text-white {{ request()->routeIs('posts.create') ? 'active bg-secondary rounded' : '' }}">
            Create New Post
        </a>
    </nav>
</aside>
