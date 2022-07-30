<div class="ui grid stackable">
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
            <button onclick="copyToClipboard()" class="ui right labeled icon button green">
                <i class="right copy icon"></i>
                Copy
            </button>
        </p>
    </div>
</div>

<script>
    function copyToClipboard() {
        var val = $('input[name="broadcaster-key"]').val();
        if (val === '') {
            window.Toastify({
                text: 'Nothing to Copy! Request a Key First!',
                duration: 3000,
                close: true,
                gravity: 'top',
                position: 'center',
                backgroundColor: '#B03060',
                textColor: '#000000',
                className: 'toast'
            }).showToast();
            return
        }
        navigator.clipboard.writeText(val);
        window.Toastify({
            text: 'Copied to clipboard',
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'center',
            backgroundColor: '#016936',
            textColor: '#ffffff',
            className: 'toast'
        }).showToast();
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