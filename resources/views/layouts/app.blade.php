<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/semantic.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/toastify.css') }}"> 

        <!-- Scripts -->
        <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
        <script src="{{ asset('js/semantic.min.js') }}" defer></script>
        <script src="{{ asset('js/sweetalert2.min.js') }}" defer></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div id="page-container" class="pusher">
            @include('layouts.nav')
            <header class="ui container">
                <h1 class="ui header">{{ $header }}</h1>
            </header>
            <main class="ui container">
                <!-- Page Content -->
                {{ $slot }}
            </main>
            @include('cookie-consent::index')
            @include('layouts.footer')
        </div>
    </body>
</html>
