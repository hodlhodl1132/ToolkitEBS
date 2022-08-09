<div id="poll-form" class="ui raised segment teal">
    <form id="poll-details" class="ui form">
        <div class="two fields">
            <div class="field">
                <label>{{ __('Title') }}</label>
                <input type="text" id="queued_poll_title" name="title" placeholder="What event should happen next?">
            </div>
            <div class="field">
                <label>{{ __('Poll Duration') }} <span id="queued_poll_duration_label"></span></label>
                <input
                 max="5"
                 min="1"
                 step="1"
                 value="2"
                 type="range" id="queued_poll_duration" name="duration">
            </div>

        </div>
    </form>

    <h3 class="ui header">{{ __('Poll Options') }}</h3>
    <div class="ui form">
        <div class="field">
            <label>{{ __('Add Poll Options') }}</label>
            <div id="incident-dropdown" class="ui floating labeled icon dropdown basic button">
                <i class="plus icon"></i>
                <span class="text">{{ __('Available Options') }}</span>
                <div class="menu incident-menu">
                </div>
            </div>
        </div>
    </div>

    <div id="incident-cards" class="ui cards">
    </div>

    <button class="ui button blue" id="submit-poll-button">{{ __('Submit Poll') }}</button>
    <button id="close-polls-button" class="ui button">Close</button>
</div>

<style>
#poll-form {
    padding: 20px;
    min-height: 40vh;
    margin-top: 20px;
}
#poll-details {
    margin-bottom: 3em;
}
#incident-cards {
    margin-top: 1em;
    margin-bottom: 1em;
}
.configure-incident-button:hover,
.remove-incident-button:hover {
    cursor: pointer;
}
.configure-incident-button:hover {
    color: #4183c4;
}
.remove-incident-button:hover {
    color: #f44336;
}
.incident-form {
    margin-top: 1em;
}
</style>
<script>
    $(document).ready(function() {
        $('#queued_poll_duration').rangeslider(
            {
                polyfill: false,
                onInit: function() {
                    $('#queued_poll_duration_label').text(2 + " Minutes");
                },
                onSlide: function(position, value) {
                    $('#queued_poll_duration_label').text(value + " Minutes");
                }
            }
        );
    })
</script>