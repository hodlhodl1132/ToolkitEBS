<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name . " Settings" }}
        </h2>
    </x-slot>

    <div class="ui top attached tabular menu">
        <a class="active item" data-tab="broadcaster-key">Broadcaster Key</a>
        {{-- <a class="item" data-tab="second">Second</a>
        <a class="item" data-tab="third">Third</a> --}}
    </div>

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

    {{-- <div class="ui bottom attached tab segment" data-tab="second">
        Second
    </div>

    <div class="ui bottom attached tab segment" data-tab="third">
        Third
    </div> --}}


    <script>

        function getBroadcasterKey()
        {
            $.ajax({
                type: "POST",
                url: '/tokens/create/onsite',
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
