<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Accounts') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        @include('components.display-errors')
    @endif

    @include('users.components.user-search')

    @include('users.components.user-table')

    @if (count($users))
        {{ $users->links() }}
    @endif
    
</x-app-layout>
