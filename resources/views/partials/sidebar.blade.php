<style>
    .site-sidebar {
        width: 220px;
        min-width: 220px;
        min-height: 100vh;
        background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
        color: var(--color-bg) !important;
    }

    .site-sidebar .nav-link {
        color: #fff !important;
        border-radius: 6px;
        padding: .6rem .9rem;
    }

    .site-sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, .2);
        font-weight: 600;
    }

    @media (max-width: 991.98px) {
        .site-sidebar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            min-width: 100%;
            min-height: auto;
            height: 64px;
            padding: 0 !important;
            z-index: 1040;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, .15);
        }

        .site-sidebar .nav {
            flex-direction: row !important;
            height: 100%;
            justify-content: space-around;
        }

        .site-sidebar .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            flex: 1;
            border-radius: 0;
            padding: .3rem;
        }

        .site-sidebar .nav-link i {
            font-size: 1.2rem;
            margin-bottom: 2px;
        }

        .page-content {
            padding-bottom: 80px !important;
        }
    }
</style>

<aside class="site-sidebar text-white p-3">
    <nav class="nav flex-column gap-2">
        <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i>
            <span>My Posts</span>
        </a>
        <a href="{{ route('posts.grid') }}" class="nav-link {{ request()->routeIs('posts.grid') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            <span>Grid View</span>
        </a>
        <a href="{{ route('posts.create') }}" class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle-fill"></i>
            <span>New Post</span>
        </a>

        <a href="{{ route('posts.allposts') }}" class="nav-link {{ request()->routeIs('posts.allposts') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            <span>All Posts</span>
        </a>

    </nav>
</aside>
