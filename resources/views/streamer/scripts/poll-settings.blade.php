<script>
$(document).ready(() => {
    const pollDurationLabel = $('#poll_duration_label')
    const pollDurationSlider = $('input[name="duration"]')
    const pollIntervalLabel = $('#poll_interval_label')
    const pollIntervalSlider = $('input[name="interval"]')

    function retrievePollSettings()
    {
        $.ajax({
            type: 'GET',
            url: '/api/settings/polls/{{ $user->provider_id }}',
            success: (data) => {
                Alpine.store('poll_settings').settings = data
            }
        })
    }

    function updatePollSettings(pollSettings)
    {
        if (pollSettings.provider_id === undefined)
        {
            retrievePollSettings()
            return
        }
        InitializeSliders()
        if (pollSettings.automated_polls == "1")
        {
            $('#automated_polls').prop('checked', true)
        }
        else
        {
            $('#automated_polls').prop('checked', false)
        }
    }

    $('#poll-settings-form').submit(function(e) {
        e.preventDefault()

        var form = $(this)
        var loader = $('#poll-settings-form-loader')
        var action = form.attr('action')

        form.hide()
        loader.addClass('hidden')

        $.ajax({
            type: 'POST',
            url: action,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: form.serialize(),
            success: function (response) {
                form.show()
                loader.removeClass('hidden')
                updatePollSettings(response)
            },
            error: function (response) {
                form.show()
                loader.removeClass('hidden')
                console.log(response)
            }
        })
    })

    Alpine.effect(() => {
        const pollSettings = Alpine.store('poll_settings').settings
        updatePollSettings(pollSettings)
    })

    // Initialize poll settings sliders
    function InitializeSliders() {
        $('#poll_duration').rangeslider({
            polyfill: false,
            onInit: function() {
                let pollDuration = Alpine.store('poll_settings').settings.duration
                pollDurationLabel.html(pollDuration)
                pollDurationSlider.val(pollDuration)
            },
            onSlide: function(position, value) {
                pollDurationLabel.html(value)
            }
        })
        $('#poll_interval').rangeslider({
            polyfill: false,
            onInit: function() {
                let pollInterval = Alpine.store('poll_settings').settings.interval
                pollIntervalLabel.html(pollInterval)
                pollIntervalSlider.val(pollInterval)
            },
            onSlide: function(position, value) {
                pollIntervalLabel.html(value)
            }
        })
    }
})
</script>