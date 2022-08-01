<p>Here you may start a session to manage settings on a channel you moderate for.</p>
<div class="ui list">
    @foreach ($access_channels as $channel)
        <div class="item">
            <a href="{{ route('dashboard.mock', ['providerId' => $channel['provider_id']]) }}">
                <button class="ui button teal">
                    {{ $channel['user']->name }}
                </button>
            </a>
        </div>
    @endforeach
</div>