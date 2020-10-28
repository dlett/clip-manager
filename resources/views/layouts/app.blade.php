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
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ in_array(Route::currentRouteName(), ['home', 'clip.show'])  ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">Clips</a>
                </li>
                <li class="nav-item {{ in_array(Route::currentRouteName(), ['curator.list', 'curator.show']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('curator.list') }}">Curators</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('curator.show', auth()->user()->name) }}">{{ auth()->user()->nickname }}</a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="row">
        @yield('content')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
