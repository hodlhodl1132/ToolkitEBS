<h2 class="ui header">{{ __("Global Poll Settings") }}</h2>
<div class="ui grid stackable">
    <div class="sixteen wide column">
        <button id="open-polls-button" class="ui button orange">{{ __("Create New Poll") }}</button>
    </div>
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

<div class="ui modal">
    <i class="close icon"></i>
    @include('streamer.components.create-poll')
</div>

{{-- Scripts / Styles --}}

<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
<style>
    #poll_duration_slider {
        margin-top: 1em;
    }
</style>
<script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>
@include('streamer.scripts.polls')