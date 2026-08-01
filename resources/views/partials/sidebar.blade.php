<aside class="site-sidebar bg-dark text-white p-3" style="width: 220px; min-height: 100vh;">
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