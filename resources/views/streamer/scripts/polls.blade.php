<script>
    $(document).ready(function() {
        const Alpine = window.Alpine

        // Inialize the incidentDef dropdown
        Alpine.store('incident_defs', {
            items: [],
            options: [],
            length: 0,

            store(items) {
                this.items = items
            },
            addOption(option) {
                let id = (+new Date).toString(36)
                this.options[id] = Object.assign({}, option)
                this.length++
                return id
            },
            removeOption(id) {
                this.length--
                delete this.options[id]
            },
        });

        Alpine.store('countdown', {
            label: "0:00",
            start(end_time) {
                let secondsLeft = end_time - Date.now() / 1000
                if (secondsLeft <= 0) {
                    this.label = "0:00"
                    $('.countdown').addClass('red')
                    $('.countdown').transition('flash')
                } else {
                    this.label = secondsToTime(secondsLeft)
                    setTimeout(this.start.bind(this, end_time), 1000)
                }
            }
        })

        Alpine.effect(() => {
            const activePoll = Alpine.store('active_poll').poll
            updateActivePoll()
        })

        $('#poll-form').hide()

        $('#open-polls-button').on('click', function() {
            if (!Alpine.store('broadcaster_live').live)
            {
                window.ErrorToast('You must be live to open a poll.')
                return;
            }
            $('#poll-form').show()
            $('#open-polls-button').hide()

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

        let selected_poll_option = $('#selected_poll_option')

        $('#add_poll_option').on('click', function() {
            if (selected_poll_option.val() === '') {
                window.ErrorToast('You must select an incident to add to the poll.')
                return;
            }
            addPollOption(selected_poll_option.val())
        })

        function addPollOption(value) {

            if (Alpine.store('incident_defs').length >= 2) {
                
                window.ErrorToast('You can only add two options to a poll')
                return
            }

            let incident_def = Alpine.store('incident_defs').items[value]
            let option_id = Alpine.store('incident_defs').addOption(incident_def)
            let option_html = 
            '<div class="card">' +
                `<input type="hidden" name="option_id" value="${option_id}">` +
                '<div class="content">' +
                    '<i class="right floated cog icon configure-incident-button"></i>' +
                    '<i class="right floated trash icon remove-incident-button"></i>' +
                    `<div class="header" x-text="$store.incident_defs.options.${option_id}.label"></div>` +
                    `<div class="description" x-text="$store.incident_defs.options.${option_id}.description"></div>` +
                '</div>' +
            '</div>'
            $('#incident-cards').append(option_html)
        }

        // Remove an incident from the poll
        $('.cards').on('click', '.remove-incident-button', async function(event) {
            event.preventDefault()
            let card = $(this).closest('.card')
            let option_id = card.find('input[name="option_id"]').val()
            await card.remove()
            Alpine.store('incident_defs').removeOption(option_id)
        })

        // Configure an incident
        $('.cards').on('click', '.configure-incident-button', function(event) {
            event.preventDefault()
            let card = $(this).closest('.card')
            if (card.find('.ui.form').length !== 0) {
                return
            }
            card.css('width', '600px')
            let option_id = card.find('input[name="option_id"]').val()
            let incident_html = 
            '<div class="ui form incident-form">' +
                '<div class="field">' +
                    '<label>' + '{{ __("Option Label") }}' + '</label>' +
                    `<input x-model="$store.incident_defs.options.${option_id}.label" type="text" name="incident_def_label">` +
                '</div>' +
                '<div class="field submit-field">' +
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

        // Submit the poll
        $('#submit-poll-button').on('click', function(event) {
            event.preventDefault()
            var proxyOptions = {...Alpine.store('incident_defs').options}
            var options = []
            for (let i = 0; i < Alpine.store('incident_defs').length; i++) {
                const option = proxyOptions[Object.keys(proxyOptions)[i]]
                options.push({
                    def_name: option.def_name,
                    mod_id: option.mod_id,
                    label: option.label,
                    description: option.description,
                })
            }
            $.ajax({
                type: 'POST',
                url: '/streamer/polls/queue/store',
                data: {
                    'title': $('#queued_poll_title').val(),
                    'provider_id': $('input[name="dashboard_provider_id"]').val(),
                    'duration': $('#queued_poll_duration').val(),
                    'options': options,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    window.InfoToast('Poll created successfully')
                    $('.ui.modal').modal('hide')
                }
            })
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
                    Alpine.store('active_poll').store(response)
                }
            })
        }

        $('.refresh-active-poll').on('click', function(event) {
            event.preventDefault()
            getActivePoll()
        })

        function updateActivePoll() {
            $('#active-poll-container').empty()
            if (Alpine.store('active_poll').poll !== undefined && Alpine.store('active_poll').poll !== null) {
                createActivePollElement()
            } else {
                // No active poll
                Alpine.store('active_poll').remove()
                let card =
                    '<div class="ui card">' +
                        '<div class="content">' +
                            '<div class="header red">' + '{{ __("No Active Poll") }}' + '</div>' +
                            '<div class="description">' + '{{ __("No poll is currently active.") }}' + '</div>' +
                        '</div>' +
                    '</div>'
                $('#active-poll-container').append(card)
            }
        }

        function createActivePollElement() {
            let active_poll = Alpine.store('active_poll').poll
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
                            `<span x-text="$store.countdown.label" x-init="$store.countdown.start(${active_poll.end_time})"></span>` +
                        '</div>' +
                        '<div class="label">' +
                            '{{ __("Time Left") }}' +
                        '</div>' +
                    '</div>'

                let header = 
                    '<div class="content">' +
                        '<div class="header">' +
                            active_poll.title +
                        '</div>' +
                    '</div>'
                
                let content = 
                        '<div class="content">' +
                            '<div class="ui list">' +
                                options_html +
                                countdown_statistic
                            '</div>' +
                        '</div>'

                let active_poll_html = 
                header +
                content

                let card = '<div class="card">' + active_poll_html + '</div>'

                $('#active-poll-container').append(card)
            }
        }

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