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
        background-color: rgba(255,255,255,.15) !important;
    }
</style>

<header class="site-header border-bottom">
    <nav class="navbar navbar-expand-lg px-3">
        <span class="navbar-brand fw-bold" style="color: var(--color-bg);">Engineer Yourself</span>

        <div class="ms-auto dropdown">
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
    </nav>
</header>