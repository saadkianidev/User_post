<style>
    :root {
        --color-primary: #06b1e2;
        --color-secondary: #de78d9;
        --color-bg: #ffffff;
    }

    .nav-link{
        background-color: var(--color-secondary) !important;
        color: var(--color-bg) !important;
        border-radius: 6px;
    }
     .nav-link:active{
         background-color: var(--color-primary) !important;
     }

    .site-sidebar {}
</style>

<aside class="site-sidebar text-white p-3" style="width: 220px; min-height: 100vh; background-color: var(--color-primary); color: var(--color-bg) !important;">
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
