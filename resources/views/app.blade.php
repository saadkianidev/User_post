<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enigneer Yourself</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> -->

    {{-- Theme: server-rendered so colors are correct even before JS runs / on a new device --}}
    @php
        $userTheme = auth()->check() ? auth()->user()->theme : null;
    @endphp
    <style>
        :root {
            --color-primary: {{ $userTheme->primary_color ?? '#4f46e5' }};
            --color-secondary: {{ $userTheme->secondary_color ?? '#22c55e' }};
            --color-bg: {{ $userTheme->bg_color ?? '#ffffff' }};
        }
    </style>

    <script>
        (function () {
            const saved = JSON.parse(localStorage.getItem('siteTheme') || '{}');
            const root = document.documentElement;
            if (saved.primary) root.style.setProperty('--color-primary', saved.primary);
            if (saved.secondary) root.style.setProperty('--color-secondary', saved.secondary);
            if (saved.bg) root.style.setProperty('--color-bg', saved.bg);
        })();
    </script>

    @stack('styles')

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.17.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.17.0/firebase-analytics.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAw49szMrxV3qPKXKixWhFv68zKeYfdm7U",
            authDomain: "social-media-app-1831e.firebaseapp.com",
            projectId: "social-media-app-1831e",
            storageBucket: "social-media-app-1831e.firebasestorage.app",
            messagingSenderId: "547602760017",
            appId: "1:547602760017:web:7d66bccf68d49a39245370",
            measurementId: "G-X3EDWS6055"
        };

        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>

</head>

<body class="site">

    @include('partials.header')

    <div class="d-flex">
        @include('partials.sidebar')
        <main class="page-content flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

    @include('partials.footer')

    @auth
        @include('partials.theme-customizer')
    @endauth

    @stack('scripts')

</body>

</html>