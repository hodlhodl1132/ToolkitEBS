<h2 class="ui header">{{ __("Global Poll Settings") }}</h2>
<div class="ui form">
    <form>
        <div class="field">
            <label>{{ __("Poll Duration (minutes):") }} <span id="poll_duration_label">2</span></label>
            <input type="range" min="1" max="5" value="2" class="slider" name="poll_duration">
        </div>
        <button class="ui primary button" tabindex="0">{{ __('Save Settings') }}</button>
    </form>
</div>

<script>
    // Update poll duration label text
    $('input[name="poll_duration"]').on('input propertychange', function() {
        $('#poll_duration_label').text($(this).val())
    })
</script>