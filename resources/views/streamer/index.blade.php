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

    <div class="ui grid">
        <div class="four wide column">
            @include('streamer.components.navigation')
        </div>
        <div class="twelve wide stretched column">
            <div class="tabs"> {{-- Start of Tab Segments --}}

                @if ($broadcaster) {{-- Broadcaster Only Tabs --}}
                <div class="ui tab segment" data-tab="broadcaster-key">
                    @include('streamer.tabs.broadcaster-key')
                </div>

                <div class="ui tab segment" data-tab="moderators">
                    @include('streamer.tabs.moderators')
                </div>

                <div class="ui tab segment" data-tab="channels">
                    @include('streamer.tabs.channels')
                </div>
                @endif

                <div class="ui tab" data-tab="polls">
                    @include('streamer.tabs.polls')
                </div>

            </div> {{-- End of Tab Segments --}}
        </div>
    </div>

</x-app-layout>