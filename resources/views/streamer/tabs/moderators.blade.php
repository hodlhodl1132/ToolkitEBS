<p>Here you may give your channel moderators access to your channel settings within the toolkit ext.</p>
<p>
    <form class="ui form" method="POST" action="{{ route('dashboard.user.add') }}">
        @csrf
        <div class="field">
            <label>Moderators</label>
            <select name="provider_id" class="ui search dropdown">
            </select>
        </div>
        <button class="ui primary button" tabindex="0">{{ __('Add Moderator') }}</button>
    </form>
</p>
<table class="ui table celled">
    <thead>
        <tr>
            <th>Name</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($moderators as $moderator)
            <tr>
                <td>{{ $moderator->name }}</td>
                <td>
                    <form class="ui form" method="POST" action="{{ route('dashboard.user.remove') }}">
                    @csrf
                        <input name="provider_id" value="{{ $moderator->provider_id }}" type="hidden">
                        <button onclick="deleteModerator" class="ui button red">
                            Remove
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>