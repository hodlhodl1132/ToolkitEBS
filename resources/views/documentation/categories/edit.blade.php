 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit ') . $pageCategory->title }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="ui warning message">
            <i class="close icon"></i>
              <div class="header">
                There were issues validating your submission
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pagecategories.update', ['id' => $pageCategory->id]) }}">
    @csrf
    <div class="container">
        <div class="ui form">
            <div class="field">
                <label>Page Category Title</label>
                <input type="text" name="title" value="{{ old('title', $pageCategory->title) }}">
            </div>
            <button class="ui primary button" tabindex="0">{{ __('Save Category') }}</button>
        </div>
    </div>
    </form>

    <p>

    @can('pages.delete')
    <form method="POST" action="{{ route('pagecategories.delete', ['id' => $pageCategory->id]) }}">
        @csrf
        <a :href="{{ route('pagecategories.delete', ['id' => $pageCategory->id]) }}">
            <button onclick="event.preventDefault();
                        this.closest('form').submit();" class="ui red right labeled icon button">
                <i class="trash alternate icon"></i>
                Delete
            </button>
        </a>
    </form>
    @endcan

    </p>
</x-app-layout>
