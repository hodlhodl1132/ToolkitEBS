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
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/semantic.min.js') }}" defer></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <style>
            .hidden.menu {
                display: none;
            }

            .masthead.segment {
                min-height: 700px;
                padding: 1em 0em;
            }
            .masthead .logo.item img {
                margin-right: 1em;
            }
            .masthead .ui.menu .ui.button {
                margin-left: 0.5em;
            }
            .masthead h1.ui.header {
                margin-top: 3em;
                margin-bottom: 0em;
                font-size: 4em;
                font-weight: normal;
            }
            .masthead h2 {
                font-size: 1.7em;
                font-weight: normal;
            }

            .ui.vertical.stripe {
                padding: 8em 0em;
            }
            .ui.vertical.stripe h3 {
                font-size: 2em;
            }
            .ui.vertical.stripe .button + h3,
            .ui.vertical.stripe p + h3 {
                margin-top: 3em;
            }
            .ui.vertical.stripe .floated.image {
                clear: both;
            }
            .ui.vertical.stripe p {
                font-size: 1.33em;
            }
            .ui.vertical.stripe .horizontal.divider {
                margin: 3em 0em;
            }

            .quote.stripe.segment {
                padding: 0em;
            }
            .quote.stripe.segment .grid .column {
                padding-top: 5em;
                padding-bottom: 5em;
            }

            .footer.segment {
                padding: 5em 0em;
            }

            .secondary.pointing.menu .toc.item {
                display: none;
            }

            @media only screen and (max-width: 700px) {
                .ui.fixed.menu {
                display: none !important;
                }
                .secondary.pointing.menu .item,
                .secondary.pointing.menu .menu {
                display: none;
                }
                .secondary.pointing.menu .toc.item {
                display: block;
                }
                .masthead.segment {
                min-height: 350px;
                }
                .masthead h1.ui.header {
                font-size: 2em;
                margin-top: 1.5em;
                }
                .masthead h2 {
                margin-top: 0.5em;
                font-size: 1.5em;
                }
            }
        </style>
    </head>
    <body>

        <!-- Following Menu -->
        <div class="ui large top fixed hidden menu">
        <div class="ui container">
            <a class="active item">Home</a>
            <a class="item">Work</a>
            <a class="item">Company</a>
            <a class="item">Careers</a>
            <div class="right menu">
            <div class="item">
                <a class="ui button">Log in</a>
            </div>
            <div class="item">
                <a class="ui primary button">Sign Up</a>
            </div>
            </div>
        </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="ui vertical inverted sidebar menu">
        <a class="active item">Home</a>
        <a class="item">Work</a>
        <a class="item">Company</a>
        <a class="item">Careers</a>
        <a class="item">Login</a>
        <a class="item">Signup</a>
        </div>


        <!-- Page Contents -->
        <div class="pusher">
        <div class="ui inverted vertical masthead center aligned segment">

            <div class="ui container">
            <div class="ui large secondary inverted pointing menu">
                <a class="toc item">
                <i class="sidebar icon"></i>
                </a>
                <a class="active item" href="{{ route('home') }}">Home</a>
                <a class="item" href="{{ route('documentation.index') }}">Documentation</a>
                <a class="item" href="{{ route('documentation.show', ['slug' => 'get_started', 'category_name' => 'setup']) }}">Get Started</a>
                <div class="right item">
                    @guest
                        <a class="ui inverted button" href="{{ route('twitch.login') }}">Log in <i class="twitch icon"></i></a>
                    @endguest
                    @auth
                        <a class="ui inverted button" href="{{ route('dashboard') }}">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a
                            class="ui inverted button"
                            :href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    @endauth
                </div>
            </div>
            </div>

            <div class="ui text container">
                <h1 class="ui inverted header">
                    Toolkit Ext
                </h1>

                <h2>A new type of Game Integration.</h2>
                <a href="{{ route('documentation.show', ['slug' => 'get_started', 'category_name' => 'setup']) }}">
                    <div  class="ui huge primary button">Get Started <i class="right arrow icon"></i></div>
                </a>
            </div>

        </div>

<div class="ui vertical stripe segment">
    <div class="ui middle aligned stackable grid container">
      <div class="row">
        <div class="eight wide column">
          <h3 class="ui header">Event Polls</h3>
          <p>Viewers can vote on the next Event! A huge list of supply drops, raids, funny situations, and more! With more events being added all the time.</p>
        </div>
        <div class="six wide right floated column">
            <img src="{{ asset('images/polls_preview.jpg') }}">
        </div>
      </div>
    </div>
  </div>


        <div class="ui inverted vertical footer segment">
            <div class="ui container">
            <div class="ui stackable inverted divided equal height stackable grid">
                <div class="three wide column">
                <h4 class="ui inverted header">Links</h4>
                <div class="ui inverted link list">
                    <a href="{{ route('view.page', ['slug' => 'privacy_policy']) }}" class="item">Privacy Policy</a>
                    <a href="{{ route('view.page', ['slug' => 'terms_of_use']) }}" class="item">Terms of Use</a>
                </div>
                </div>
                <div class="three wide column">
                <h4 class="ui inverted header">Social Media</h4>
                <div class="ui inverted link list">
                    <a href="https://discord.gg/rEYEFhb" class="item">Discord</a>
                </div>
                </div>
                {{-- <div class="seven wide column">
                <h4 class="ui inverted header">Footer Header</h4>
                <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
                </div> --}}
            </div>
            </div>
        </div>
        </div>

    </body>
</html>
