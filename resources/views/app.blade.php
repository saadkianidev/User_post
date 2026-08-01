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
