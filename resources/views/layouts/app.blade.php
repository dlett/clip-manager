<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title') {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('head')
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 mt-5">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Clips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Search</a>
                </li>
            </ul>
        </div>

        @yield('content')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
