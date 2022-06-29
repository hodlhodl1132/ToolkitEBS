<h2 class="ui header">{{ __('Create Poll') }}</h2>

<div id="poll-form" class="container">

<form id="poll-details" class="ui form">
    <div class="three fields">
        <div class="field">
            <label>{{ __('Poll Name') }}</label>
            <input type="text" placeholder="What event should happen next?">
        </div>
    </div>
</form>

<h3 class="ui header">{{ __('Poll Options') }}</h3>

<div id="incident-cards" class="ui cards">
</div>

<form id="incident-options-form" class="ui form">
    <div class="three fields">
        <div class="field">
            <div class="ui selection dropdown">
                <input type="hidden" name="gender">
                <i class="dropdown icon"></i>
                <div class="default text">Available Incidents</div>
                <div class="menu incident-menu">
                </div>
            </div>
        </div>

        <div class="field">
            <button id="add-incident-button" class="ui button orange">Add Incident</button>
        </div>
    </div>
</form>

</div>



<style>
#poll-form {
    padding: 20px;
}
#poll-details {
    margin-bottom: 3em;
}
#incident-cards {
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