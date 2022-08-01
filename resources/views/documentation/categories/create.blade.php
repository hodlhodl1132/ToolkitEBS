<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Page Category') }}
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

    <form method="POST" action="{{ route('pagecategories.store') }}">
    @csrf
    <div class="container">
        <div class="ui form">
            <div class="field">
                <label>Page Category Title</label>
                <input type="text" name="title" value="{{ old('title') }}">
            </div>
            <button class="ui primary button" tabindex="0">{{ __('Save Category') }}</button>
        </div>
    </div>
    </form>
</x-app-layout>
