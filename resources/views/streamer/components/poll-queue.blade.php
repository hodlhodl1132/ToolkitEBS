<div class="ui grid stackable padded">
    <div class="twelve wide column">
        <h3 class="ui header">{{ __("Poll Queue") }}</h3>
    </div>
    <div class="three wide column right floated">
        <div class="ui buttons">
            <button class="ui icon basic button blue refresh-poll-queue" data-tooltip="Check for active polls">
                <i class="refresh icon"></i>
            </button>
        </div>
    </div>
    <div id="poll-queue-container" class="sixteen wide column">
        <table class="ui table">
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