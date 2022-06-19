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