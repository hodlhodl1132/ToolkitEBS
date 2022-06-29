<script>
    $(document).ready(function() {
        // Inialize the sliders
        $( "#poll_duration_slider" ).slider({
            range: "min",
            min: 1,
            max: 5,
            value: $('input[name="duration"]').val(),
            slide: function( event, ui ) {
                $("#poll_duration_label").text(ui.value)
                $('input[name="duration"]').val(ui.value)
            }
        });
        $( "#poll_interval_slider" ).slider({
            range: "min",
            min: 1,
            max: 30,
            value: $('input[name="interval"]').val(),
            slide: function( event, ui ) {
                $("#poll_interval_label").text(ui.value)
                $('input[name="interval"]').val(ui.value)
            }
        });
        $("#poll_interval_label").text($('input[name="interval"]').val())
        $("#poll_duration_label").text($('input[name="duration"]').val())

        const Alpine = window.Alpine

        // Inialize the incidentDef dropdown
        Alpine.store('incident_defs', {
            items: [],
            options: [],

            store(items) {
                this.items = items
            },
            addOption(option) {
                let id = (+new Date).toString(36)
                this.options[id] = Object.assign({}, option)
                return id
            },
        });

        $('#open-polls-button').on('click', function() {
            $('.ui.modal').modal('show')

            if (Alpine.store('incident_defs').items.length === 0) {
                $.ajax({
                    type: 'GET',
                    url: '/streamer/incident-defs/{{ $user->provider_id }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        window.Alpine.store('incident_defs').store(response)
                        let incident_defs = Alpine.store('incident_defs').items
                        Object.keys(incident_defs).forEach(function(key) {
                            $('.incident-menu').append(
                                '<div class="item" data-value="' + [key] + '">' + incident_defs[key].label + '</div>'
                            )
                        })
                        $('.ui.dropdown').dropdown()
                    }
                })
            }
        })

        // Add a poll option
        $('#add-incident-button').on('click', function(event) {
            event.preventDefault()
            let incident_id = $(this).closest('.ui.form').find('.ui.dropdown').dropdown('get value')
            let incident_def = Alpine.store('incident_defs').items[incident_id]
            let option_id = Alpine.store('incident_defs').addOption(incident_def)
            let option_html = 
            '<div class="card">' +
                `<input type="hidden" name="option_id" value="${option_id}">` +
                '<div class="content">' +
                    '<i class="right floated cog icon configure-incident-button"></i>' +
                    '<i class="right floated trash icon remove-incident-button"></i>' +
                    `<div class="header" x-text="$store.incident_defs.options.${option_id}.label"></div>` +
                    `<div class="description" x-text="$store.incident_defs.options.${option_id}.letter_text"></div>` +
                '</div>' +
            '</div>'
            $('#incident-cards').append(option_html)
        })

        // Remove an incident from the poll
        $('.cards').on('click', '.remove-incident-button', function(event) {
            event.preventDefault()
            $(this).closest('.card').remove()
        })

        // Configure an incident
        $('.cards').on('click', '.configure-incident-button', function(event) {
            event.preventDefault()
            $(this).closest('.card').css('width', '600px')
            let option_id = $(this).closest('.card').find('input[name="option_id"]').val()
            let incident_html = 
            '<div class="ui form incident-form">' +
                '<div class="fields">' +
                    '<div class="eight wide field">' +
                        '<label>' + '{{ __("Label") }}' + '</label>' +
                        `<input x-model="$store.incident_defs.options.${option_id}.label" type="text" name="incident_def_label">` +
                    '</div>' +
                    '<div class="eight wide field">' +
                        '<label>' + '{{ __("Letter Label") }}' + '</label>' +
                        `<input type="text" x-model="$store.incident_defs.options.${option_id}.letter_label">` +
                    '</div>' +
                '</div>' +
                '<div class="fields">' +
                    '<div class="eight wide field">' +
                        '<label>' + '{{ __("Letter Text") }}' + '</label>' +
                        `<textarea x-model="$store.incident_defs.options.${option_id}.letter_text" rows="2"></textarea>` +
                    '</div>' +
                '</div>' +
                '<div class="field">' +
                    '<button class="ui button primary save-incident-button" type="submit">' + '{{ __("Save") }}' + '</button>' +
                '</div>' +
            '</div>'
            $(this).closest('.card .content').append(incident_html)
        })

        // Save the incident
        $('.cards').on('click', '.save-incident-button', function(event) {
            event.preventDefault()
            let form = $(this).closest('.ui.form')
            $(this).closest('.card').css('width', '290px')
            form.remove()
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

        // Get active poll
        getActivePoll()

        function getActivePoll() {
            $('#active-poll-container').empty()
            $('#active-poll-container').append('<div class="ui active centered inline loader"></div>')
            $.ajax({
                method: 'GET',
                url: '/streamer/polls/active-poll/{{ $user->provider_id }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    updateActivePoll(response)
                }
            })
        }

        $('.refresh-active-poll').on('click', function(event) {
            event.preventDefault()
            getActivePoll()
        })

        function updateActivePoll(response) {
            $('#active-poll-container').empty()
            if (response !== undefined) {
                Alpine.store('active_poll').store(response)
                createActivePollElement()
            } else {
                // No active poll
                Alpine.store('active_poll').remove()
                let no_active_poll_message = 
                '<h4 class="ui header red">' + '{{ __("No Active Poll") }}' + '</h4>'
                $('#active-poll-container').append(no_active_poll_message)
            }
        }

        function createActivePollElement() {
            let active_poll = Alpine.store('active_poll').poll
            console.log(active_poll)
            if (active_poll !== null) {
                let options_html = ''
                active_poll.options.forEach(function(item) {
                    options_html +=
                        '<div class="item">' +
                            item.label +
                        '</div>'
                })

                let countdown_statistic = 
                    '<div class="ui statistic countdown">' +
                        '<div class="value">' +
                            '<span x-text="$store.countdown.label" x-init="$store.countdown.start()"></span>' +
                        '</div>' +
                        '<div class="label">' +
                            '{{ __("Time Left") }}' +
                        '</div>' +
                    '</div>'

                let active_poll_html = 
                '<h4 class="ui header">' + active_poll.title + '</h4>' +
                '<div class="ui list bulleted">' +
                    options_html +
                '</div>' +
                countdown_statistic

                $('#active-poll-container').append(active_poll_html)
            }
        }

        Alpine.store('countdown', {
            label: "",
            start() {
                const active_poll = Alpine.store('active_poll').poll
                let secondsLeft = active_poll.end_time - Date.now() / 1000
                if (secondsLeft <= 0) {
                    this.label = "0:00"
                    $('.countdown').addClass('red')
                    $('.countdown').transition('flash')
                } else {
                    this.label = secondsToTime(secondsLeft)
                    setTimeout(this.start.bind(this), 1000)
                }
            }
        })

        function secondsToTime(secs) {
            let hours = Math.floor(secs / (60 * 60))
            let divisor_for_minutes = secs % (60 * 60)
            let minutes = Math.floor(divisor_for_minutes / 60)
            let divisor_for_seconds = divisor_for_minutes % 60
            let seconds = Math.ceil(divisor_for_seconds)
            if (seconds == 60) {
                seconds = 0
                minutes += 1
            }
            let time = minutes + ":" + (seconds < 10 ? '0' : '') + seconds
            return time
        }
    })
</script>