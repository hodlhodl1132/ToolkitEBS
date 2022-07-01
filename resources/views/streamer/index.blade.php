<x-app-layout>
    @include('streamer.scripts.main')
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name . " Settings" }}
        </h2>
    </x-slot>

    <input name="dashboard_provider_id" type="hidden" value="{{ $user->provider_id }}">

    @if ($errors->any())
        @include('components.display-errors')
    @endif

    @if (!$broadcaster)
        @include('streamer.components.mock-message')
    @endif

    @include('streamer.components.stats-header')

    <div class="tabs"> {{-- Start of Tab Segments --}}

        <div class="ui top attached tabular menu">
            @if ($broadcaster)
                <a class="item" data-tab="broadcaster-key">Broadcaster Key</a>
                <a class="item" data-tab="moderators">Moderators</a>
                <a class="item" data-tab="channels">Channels</a>
            @endif
            <a class="item" data-tab="polls">Polls</a>
        </div>

        @if ($broadcaster) {{-- Broadcaster Only Tabs --}}
        <div class="ui bottom attached tab segment" data-tab="broadcaster-key">
            @include('streamer.tabs.broadcaster-key')
        </div>

        <div class="ui bottom attached tab segment" data-tab="moderators">
            @include('streamer.tabs.moderators')
        </div>

        <div class="ui bottom attached tab segment" data-tab="channels">
            @include('streamer.tabs.channels')
        </div>
        @endif

        <div class="ui bottom attached tab segment" data-tab="polls">
            @include('streamer.tabs.polls')
        </div>

    </div> {{-- End of Tab Segments --}}
</x-app-layout>
