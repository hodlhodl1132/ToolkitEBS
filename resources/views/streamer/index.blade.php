<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name . " Settings" }}
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

    <div class="ui top attached tabular menu">
        @if ($broadcaster)
            <a class="active item" data-tab="broadcaster-key">Broadcaster Key</a>
            <a class="item" data-tab="moderators">Moderators</a>
        @endif
        <a class="item" data-tab="polls">Polls</a>
        <a class="item" data-tab="channels">Channels</a>
    </div>

    @if ($broadcaster)
    <div class="ui bottom attached active tab segment" data-tab="broadcaster-key">
        <div class="ui grid">
            <div class="eight wide column">
                <h2 class="ui header">Broadcaster Key</h2>
                <p>You will need to create a new key every 90 days.</p>
                <p>You can request a new broadcaster key from the button below. If you've used a broadcaster key in the past, it will not work after creating a new one.</p>
                <p><b>If you lose your key, or forget to copy it, you will have to request another key. We don't store your key on this page for future use.</b></p>
                <p>
                    <div class="ui fluid input">
                        <input name="broadcaster-key" type="text" disabled>
                    </div>
                </p>
                <p>
                    <button onclick="getBroadcasterKey()" class="ui button red">New Broadcaster Key</button>
                </p>
            </div>
        </div>
    </div>

    <div class="ui bottom attached tab segment" data-tab="moderators">
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
    </div>

    <script>
        $.ajax({
            type: 'GET',
            url: '{{ route('twitchapi.getmoderators') }}',
            headers : {'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')},
            success: (response) => {
                if (response.moderators !== 'undefined')
                {
                    response.moderators.forEach(moderator => {
                        $('select[name="provider_id"]').append(
                            '<option value="' +
                            moderator.provider_id +
                            '">' +
                            moderator.user_name +
                            '</option>'
                        )
                    });
                }
            }
        })

        function deleteModerator(button)
        {
            $.ajax({
                type: 'POST',
                url: '',
                headers : {'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')},
                success: (response) => {
                    console.log(response)
                }
            })
        }
    </script>
    @endif

    <div class="ui bottom attached tab segment" data-tab="polls">
    </div>

    <div class="ui bottom attached tab segment" data-tab="channels">
    </div>

    <script>

        function getBroadcasterKey()
        {
            $.ajax({
                type: 'POST',
                url: '{{ route('tokens.create') }}',
                headers : {'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')},
                success: (response) => {
                    if (response.token !== 'undefined')
                    {
                        $('input[name="broadcaster-key"]').attr('value', response.token)
                    }
                }
            })
        }
    </script>
</x-app-layout>
