<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Accounts') }}
        </h2>
    </x-slot>

    <table class="ui celled table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Provider ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td data-label="Name">{{ $user->name }}</td>
                    <td data-label="Provider Id">{{ $user->provider_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</x-app-layout>
