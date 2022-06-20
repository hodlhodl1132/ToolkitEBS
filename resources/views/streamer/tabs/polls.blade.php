<h2 class="ui header">{{ __("Global Poll Settings") }}</h2>
<div class="ui grid stackable">
    <div class="four wide column">
        <div class="ui form">
            <form>
                <div class="field">
                    <label>{{ __("Poll Duration (minutes):") }} <span id="poll_duration_label">2</span></label>
                    <input type="hidden" value="3" name="poll_duration">
                    <div id="poll_duration_slider"></div>
                </div>
                <button class="ui primary button" tabindex="0">{{ __('Save Settings') }}</button>
            </form>
        </div>
    </div>

</div>

{{-- Scripts / Styles --}}

<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
<style>
    #poll_duration_slider {
        margin-top: 1em;
    }
</style>
<script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>
<script>
    $(document).ready(function() {
        $( "#poll_duration_slider" ).slider({
            range: "min",
            min: 1,
            max: 5,
            value: $('input[name="poll_duration"]').val(),
            slide: function( event, ui ) {
                $("#poll_duration_label").text(ui.value)
                $('input[name="poll_duration"]').val(ui.value)
            }
        });
        $("#poll_duration_label").text($('input[name="poll_duration"]').val())
    })
</script>