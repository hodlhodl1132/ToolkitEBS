<script>
    $(document).ready(function () {
        const Alpine = window.Alpine
        const providerId = $('input[name="dashboard_provider_id"]').val();

        // Change default tab when url param is set
        const queryString = window.location.search
        const urlParams = new URLSearchParams(queryString)
        const tab = urlParams.get('tab')
        if (tab !== null)
        {
            $('.menu .item').tab('change tab', tab)
        } else {
            $('.menu > .item:first-of-type').addClass('active')
            $('div.tabs > div:nth-child(1)').addClass('active')
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

        const event = new Event('broadcaster_live')

        function setOffline() {
            Alpine.store('broadcaster_live').set(false)
            $('#live-button').css('display', 'none')
        }

        function setOnline() {
            Alpine.store('broadcaster_live').set(true)
            $('#live-button').css('display', 'inline-block')
        }

        // Global store

        Alpine.store('poll_settings', {
            settings: {}
        })
        Alpine.store('poll_queue', {
            queue: [],
            push(poll) {
                this.queue = $.merge(this.queue, [poll])
            }
        })
        Alpine.store('active_poll', {
            poll: null,
            store(poll) {
                this.poll = poll
            },
            remove() {
                this.poll = null
            }
        })

        Echo.join(`dashboard.${providerId}`)
            .listen('PollCreated', (e) => {
                window.InfoToast('A new poll is active')
                Alpine.store('active_poll').store(e)
            })
            .listen('PollDeleted', (e) => {
                window.InfoToast('The active poll has ended')
                Alpine.store('active_poll').remove()
            })
            .listen('PollSettingsUpdate', (e) => {
                Alpine.store('poll_settings').settings = e
            })
            .listen('QueuedPollCreated', (e) => {
                if (e.id !== undefined) {
                    Alpine.store('poll_queue').push(e)
                }
            })
            .listen('QueuedPollValidated', (e) => {
                if (e.id !== undefined) {
                    Alpine.store('poll_queue').queue = $.map(Alpine.store('poll_queue').queue, (poll) => {
                        if (poll.id === e.id) {
                            poll.validated = e.validated
                            poll.validation_error = e.validation_error
                        }
                        return poll
                    })
                }
            })
            .listen('QueuedPollDeleted', (e) => {
                window.AlertToast('A Poll has been deleted')
                if (e.id !== undefined) {
                    if (Alpine.store('poll_queue').queue.length === 1) {
                        Alpine.store('poll_queue').queue = []
                    }

                    Alpine.store('poll_queue').queue = $.grep(Alpine.store('poll_queue').queue, (poll) => {
                        return poll.id !== e.id
                    })
                }
            })

        console.log('Hello fellow developer or person who accidentally opened the console!')
    })
</script>