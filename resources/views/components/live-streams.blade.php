<div class="ui cards">
    @foreach ($streams as $stream)
        @if ($stream->user_name != null)
        <div class="card">
            <div class="blurring dimmable image">
                <div class="ui dimmer">
                    <div class="content">
                        <div class="center">
                            <div class="ui inverted button">Visit Channel</div>
                        </div>
                    </div>
                </div>
                <img src="{{ $stream->thumbnailLink() }}" alt="{{ $stream->title }}">
            </div>
            <div class="content">
                <div class="header">
                    <a href="{{ $stream->channelLink() }}" target="_blank">
                        {{ $stream->title }}
                    </a>
                </div>
                <div class="meta">
                    <span>{{ $stream->user_name }}</span>
                </div>
            </div>
            <div class="extra content">
                <a>
                    <i class="users icon"></i>
                    {{ $stream->viewer_count }} Viewers
                </a>
            </div>
        </div>
        @endif
    @endforeach
</div>