<div class="ui yellow message">
    <i class="close icon"></i>
    <div class="header">
        Currently Viewing Dashboard for {{ $user->name }}
    </div>
    <p><a
    href="{{ route('dashboard') }}">
        Click here
    </a> 
        to go back to your Dashboard.
    </p>
</div>