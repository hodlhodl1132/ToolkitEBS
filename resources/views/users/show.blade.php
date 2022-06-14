<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="ui warning message">
            <i class="close icon"></i>
              <div class="header">
                There were issues validating your submission
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ui cards">
        <div class="card">
            <div class="content">
                <div class="header">{{ $user->name }}</div>
                <div class="meta">Streamer</div>
            </div>
                <a href="{{ 'https://www.twitch.tv/' . $user->name }}" target="_blank" >
                <div class="ui purple bottom attached button">
                <i class="white twitch icon"></i>
                    Twitch
                </div>
                <a>
        </div>
    </div>
    <div class="container permissions-container">
        <h3 class="ui header">Permissions</h3>
        <table class="ui very basic collapsing table">
            <thead>
                <tr>
                    <th>Permission Name</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <tr><td><h5 class="ui header">Role Permissions</h5></td></tr>
                @foreach ($user->getPermissionsViaRoles() as $perm)
                    <tr>
                        <td aria-label="permission-name">{{ $perm->name }}</td>
                        <td aria-label="remove"></td>
                    </tr>
                @endforeach
                <tr><td><h5 class="ui header">Direct Permissions</h5></td></tr>
                @foreach ($user->getDirectPermissions() as $perm)
                    <tr>
                        <td aria-label="permission-name">{{ $perm->name }}</td>
                        <td aria-label="remove">
                            @can('admin.users.edit')
                                <form method="POST" action="{{ route('permission.delete') }}">
                                    @csrf
                                    <input type="hidden" value="{{ $perm->id }}" name="id">
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <a :href="{{ route('permission.delete') }}">
                                    <i onclick="event.preventDefault();
                                        this.closest('form').submit();"
                                        class="trash alternate icon"></i>
                                    </a>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @can('admin.users.edit')
        <div class="ui form">
            <form method="POST" action="{{ route('permission.store') }}">
                @csrf
                <input type="hidden" value="{{ $user->id }}" name="user_id">
                <div class="field">
                    <label>Permission</label>
                    <select class="ui search dropdown" name="id">
                        <option value="">Select Permission</option>
                        @foreach ($permissions as $perm)
                            <option value="{{ $perm->id }}">{{ $perm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="ui primary button" tabindex="0">{{ __('Add Permission') }}</button>
            </form>
        </div>
        @endcan

    </div>
</x-app-layout>
