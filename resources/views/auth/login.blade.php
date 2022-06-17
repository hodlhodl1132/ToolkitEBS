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

        <style type="text/css">
            body {
                background-color: #DADADA;
            }
            body > .grid {
                height: 100%;
            }
            .image {
                margin-top: -100px;
            }
            .column {
                max-width: 450px;
            }
        </style>

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
    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <h2 class="ui violet header">
            <div class="content">
                Log-in to ToolkitExt Dashboard
            </div>
            </h2>
            <form class="ui large form">
            <div class="ui stacked segment">
                <div class="field">
                    <p>You may login using your Twitch Account by clicking the button below.</p>
                </div>
                <a href="{{ route('twitch.login') }}">
                    <div class="ui fluid large violet submit button">
                        Login <i class="ui icon twitch"></i>
                    </div>
                </a>
            </div>
            </form>
        </div>
    </div>
    </body>
</html>
