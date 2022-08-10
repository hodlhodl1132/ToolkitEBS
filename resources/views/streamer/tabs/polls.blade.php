<h2 class="ui header">{{ __("Polls Dashboard") }}</h2>

<div class="ui raised segment teal">
<h3 class="ui header">{{ __("Active Poll") }}</h3>
<div class="ui cards" id="active-poll-container">
</div>
</div>

<div id="poll-creation">
    @include('streamer.components.create-poll')
</div>

@include('streamer.components.poll-queue')



<div class="ui raised segment teal">
    <h3 class="ui header">{{ __("Poll Settings") }}</h3>
    <div class="ui grid">
        <div class="six wide column">
            <div id="poll-settings-form-loader" class="ui text loader hidden">{{ __("Loading") }}</div>
            <div class="ui form">
                <form id="poll-settings-form" method="POST" action="{{ route('dashboard.savesettings') }}">
                    @csrf
                    <input type="hidden" name="provider_id" value="{{ $user->provider_id }}">
                    <div class="field">
                        <label>
                            <h5 class="ui header">
                                {{ __("Enable Automated Polls") }}
                            </h5>
                        </label>
                        <div class="ui checkbox">
                            <input type="hidden" name="automated_polls" value="0">
                            <input type="checkbox" name="automated_polls" id="automated_polls" value="1">
                            <label for="automated">{{ __("Enabled") }}</label>
                        </div>
                    </div>
                    <div class="field">
                        <label>
                            <h5 class="ui header">
                                {{ __("Poll Duration:") }}
                                <span id="poll_duration_label"></span> Minutes
                            </h5>
                        </label>
                        <label><small>Time Given to Viewers for Voting</small></label>
                        <input
                        max="5"
                        min="1"
                        step="1"
                        value="2"
                        type="range"
                        id="poll_duration"
                        name="duration">
                    </div>
                    <div class="field">
                        <label>
                            <h5 class="ui header">
                                {{ __("Poll Interval:") }}
                                <span id="poll_interval_label"></span> Minutes
                            </h5>
                        </label>
                        <label><small>Time Between New Polls</small></label>
                        <input
                        max="30"
                        min="1"
                        step="1"
                        value="2"
                        type="range"
                        id="poll_interval"
                        name="interval">
                    </div>
                    <button class="ui primary button" tabindex="0">{{ __('Save Settings') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('streamer.scripts.poll-settings')
@include('streamer.scripts.poll-queue')

</div>

{{-- Scripts / Styles --}}

<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/rangeslider.css') }}">
<style>
    #poll_duration_slider {
        margin-top: 1em;
    }
    .incident-form .submit-field {
        padding-top: .5em;
    }
</style>
<script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>
<script src="{{ asset('js/rangeslider.min.js') }}"></script>
@include('streamer.scripts.polls')