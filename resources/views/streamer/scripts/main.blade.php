<script>
    $(document).ready(function () {
        let broadcasterLive = false
        const providerId = $('input[name="provider_id"]').val();

        // Change default tab when url param is set
        const queryString = window.location.search
        const urlParams = new URLSearchParams(queryString)
        const tab = urlParams.get('tab')
        if (tab !== null)
        {
            $('.menu .item').tab('change tab', tab)
        } else {
            $('.menu > .item:first-of-type').addClass('active')
            $('div.tabs > div:nth-child(2)').addClass('active')
            $('.menu .item').tab()
        }

        // query live streams every minute
        getLiveStreams()

        setTimeout(() => {
            getLiveStreams()
        }, 60 * 1000)

        // query streams
        function getLiveStreams() {
            $.ajax({
                type: 'POST',
                url: '{{ route('api.streams') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    provider_id: providerId
                },
                success: function (response) {
                    updateDashboard(response)
                }
            })
        }

        function updateDashboard(response) {
            if (response.status !== undefined) 
                setOffline()
            else 
                setOnline()
        }

        function setOffline() {
            broadcasterLive = false
            $('.live-status').html('Offline')
            $('#live-button').css('display', 'none')
        }

        function setOnline() {
            broadcasterLive = true
            $('.live-status').html('Live')
            $('#live-button').css('display', 'inline-block')
        }
    })
</script>