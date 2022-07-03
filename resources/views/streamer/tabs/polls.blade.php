<h2 class="ui header">{{ __("Polls Dashboard") }}</h2>
<div class="ui segment container">
    <div class="ui grid stackable padded">
        <div class="twelve wide column">
            <h3 class="ui header">{{ __("Poll Management") }}</h3>
        </div>
        <div class="three wide column right floated">
            <div class="ui buttons">
                <button id="open-polls-button" class="ui basic black button" data-tooltip="Create and queue a new poll">{{ __("Queue Poll") }}</button>
                <button class="ui icon basic button blue refresh-active-poll" data-tooltip="Check for active polls">
                    <i class="refresh icon"></i>
                </button>
            </div>
        </div>

        <div id="active-poll-container" class="sixteen wide column">
        </div>
    </div>
</div>

<div class="ui segment">
    <h3 class="ui header">{{ __("Poll Settings") }}</h3>
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
</div>

</div>

<div class="ui longer modal scrolling">
    <i class="close icon"></i>
    @include('streamer.components.create-poll')
</div>

{{-- Scripts / Styles --}}

<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
<style>
    #poll_duration_slider {
        margin-top: 1em;
    }
    .incident-form .submit-field {
        padding-top: .5em;
    }
</style>
<script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>
@include('streamer.scripts.polls')