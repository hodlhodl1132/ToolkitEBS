<div class="header">{{ __('Create Poll') }}</div>

<div id="poll-form" class="scrolling content">

    <form id="poll-details" class="ui form">
        <div class="two fields">
            <div class="field">
                <label>{{ __('Title') }}</label>
                <input type="text" id="queued_poll_title" name="title" placeholder="What event should happen next?">
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
</div>

<div class="actions">
    <button class="ui button blue" id="submit-poll-button">{{ __('Submit Poll') }}</button>
</div>


<style>
#poll-form {
    padding: 20px;
    min-height: 40vh;
}
#poll-details {
    margin-bottom: 3em;
}
#incident-cards {
    margin-top: 1em;
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