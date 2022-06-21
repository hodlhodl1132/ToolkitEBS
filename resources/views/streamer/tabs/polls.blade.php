<h2 class="ui header">{{ __("Global Poll Settings") }}</h2>
<div class="ui grid stackable">
    <div class="six wide column">
        <div class="ui form">
            <form method="POST" action="{{ route('dashboard.savesettings') }}">
                @csrf
                <input type="hidden" name="provider_id" value="{{ $user->provider_id }}">
                <div class="field">
                    <label>
                        <h5 class="ui header">
                            {{ __("Poll Duration:") }}
                             <span id="poll_duration_label">2</span> Minutes
                        </h5>
                    </label>
                    <label><small>Time Given to Viewers for Voting</small></label>
                    <input type="hidden" value="{{ $poll_settings->duration }}" name="duration">
                    <div id="poll_duration_slider"></div>
                </div>
                <div class="field">
                    <label>
                        <h5 class="ui header">
                            {{ __("Poll Interval:") }}
                             <span id="poll_interval_label">2</span> Minutes
                        </h5>
                    </label>
                    <label><small>Time Between New Polls</small></label>
                    <input type="hidden" value="{{ $poll_settings->interval }}" name="interval">
                    <div id="poll_interval_slider"></div>
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
    })
</script>