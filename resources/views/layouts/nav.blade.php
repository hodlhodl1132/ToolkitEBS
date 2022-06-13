<div class="ui fixed inverted menu">
    <div class="ui container">
        <a href="{{ route('home') }}" class="header item">
            ToolkitExt
        </a>
        <a href="{{ route('documentation.index') }}" class="item">Documentation</a>
        <a href="{{ route('documentation.show', ['slug' => 'get_started']) }}" class="item">Get Started</a>

        <div class="right item">
            @guest
                <a class="item" href="{{ route('twitch.login') }}">Log in <i class="twitch icon"></i></a>
            @endguest
            @auth
            <form method="POST" action="{{ route('logout') }}">
            <div class="ui simple dropdown item">
                {{ Auth::user()->name }} <i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="{{ route('dashboard') }}">Dashboard</a>
                    <div class="divider"></div>
                    
                    @csrf
                    <a
                    class="item"
                    :href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }} <i class="sign-in icon"></i>
                    </a>
                        
                </div>
            </div>
            </form>
            @endauth
        </div>

    </div>
</div>