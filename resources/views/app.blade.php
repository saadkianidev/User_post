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
    <!-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> -->


    <!-- Firebase App (core) -->
    <script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-app-compat.js"></script>

    <!-- Add the products you need -->
    <script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-firestore-compat.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyAw49szMrxV3qPKXKixWhFv68zKeYfdm7U",
            authDomain: "social-media-app-1831e.firebaseapp.com",
            projectId: "social-media-app-1831e",
            storageBucket: "social-media-app-1831e.firebasestorage.app",
            messagingSenderId: "547602760017",
            appId: "1:547602760017:web:7d66bccf68d49a39245370",
            measurementId: "G-X3EDWS6055"
        };

        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.firestore();
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

</body>

</html>
