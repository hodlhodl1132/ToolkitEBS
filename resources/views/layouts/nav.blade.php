<div class="ui fixed inverted menu">
    <div class="ui container">
        <a href="{{ route('home') }}" class="header item">
            ToolkitExt
        </a>
        <a href="{{ route('documentation.index') }}" class="item">Documentation</a>
        <a href="#" class="item">Get Started</a>

        <div class="right item">
            @guest
                <a class="item" href="{{ route('twitch.login') }}">Log in <i class="twitch icon"></i></a>
            @endguest
            @auth
            <div class="ui simple dropdown item">
                {{ Auth::user()->name }} <i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="{{ route('dashboard') }}">Dashboard</a>
                    <div class="divider"></div>
                    <a class="item" href="{{ route('logout') }}">Logout <i class="sign-in icon"></i></a>
                </div>
            </div>
            @endauth
        </div>


    </div>
</div>