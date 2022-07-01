<script>
    $(document).ready(function () {
        const Alpine = window.Alpine
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

        Alpine.store('broadcaster_live', {
            live: false,
            get() {
                return this.live ? "Live" : "Offline"
            },
            set(value) {
                this.live = value
            },
            start() {
                $('.live-status').attr('x-text', '$store.broadcaster_live.get()')
            }
        })

        // query live streams every minute
        getLiveStreams()
        Alpine.store('broadcaster_live').start()

        setInterval(() => {
            getLiveStreams()
        }, 10 * 1000)

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

        const event = new Event('broadcaster_live')

        function setOffline() {
            Alpine.store('broadcaster_live').set(false)
            $('#live-button').css('display', 'none')
        }

        function setOnline() {
            Alpine.store('broadcaster_live').set(true)
            $('#live-button').css('display', 'inline-block')
        }
    })
</script>