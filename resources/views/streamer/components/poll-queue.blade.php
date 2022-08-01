<div class="ui grid stackable">
    <div class="two wide column">
        <div class="ui buttons">
            <button id="open-polls-button" class="ui basic black button" data-tooltip="Create and queue a new poll">{{ __("Queue Poll") }}</button>
        </div>
    </div>
    <div id="poll-queue-container" class="sixteen wide column">
        <table class="ui striped table">
            <thead>
                <tr>
                    <th>{{ __("Poll") }}</th>
                    <th>{{ __("Length") }}</th>
                    {{-- <th>{{ __("Delay") }}</th> --}}
                    <th>{{ __("Options") }}</th>
                    <th>{{ __("Created") }}</th>
                    <th>{{ __("Actions") }}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>