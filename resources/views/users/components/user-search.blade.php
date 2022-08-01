<form class="ui form" method="POST" action="{{ route('admin.users.search') }}">
    @csrf
    <div class="field">
        <div class="fields">
            <div class="six wide field">
                <div class="ui left icon input">
                    <input name="search" type="text" placeholder="Search users...">
                    <i class="users icon"></i>
                </div>
            </div>
            <div class="four wide field">
                <button class="ui primary button" tabindex="0">{{ __('Search') }}</button>
            </div>
        </div>
    </div>
</form>