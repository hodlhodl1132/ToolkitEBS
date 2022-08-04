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
        pollDurationLabel.html(pollSettings.duration)
        pollIntervalLabel.text(pollSettings.interval)
        pollDurationSlider.val(pollSettings.duration)
        pollIntervalSlider.val(pollSettings.interval)
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
        $( "#poll_duration_slider" ).slider({
            range: "min",
            min: 1,
            max: 5,
            value: pollDurationSlider.val(),
            slide: function( event, ui ) {
                pollDurationLabel.text(ui.value)
                pollDurationSlider.val(ui.value)
            }
        });
        $( "#poll_interval_slider" ).slider({
            range: "min",
            min: 1,
            max: 30,
            value: pollIntervalSlider.val(),
            slide: function( event, ui ) {
                pollIntervalLabel.text(ui.value)
                pollIntervalSlider.val(ui.value)
            }
        });
    }
})
</script>