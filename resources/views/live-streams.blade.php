<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ToolkitExt Streams') }}
        </h2>
    </x-slot>

    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="six wide column">
                <h3 class="ui header">Currently Live</h3>
            </div>
            <div class="twelve wide column">
                @include('components.live-streams')
            </div>
        </div>
    </div>
</x-app-layout>
