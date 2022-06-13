<x-app-layout>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#new-page-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
        });
    </script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Page') }}
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

    <form method="POST" action="{{ route('documentation.store') }}">
    @csrf
    <div class="container">
        <div class="ui form">
            <div class="field">
                <label>Page Title</label>
                <input type="text" name="title" value="{{ old('title') }}">
            </div>
            <div class="field">
                <label>Page Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}">
            </div>
            <div class="field">
                <label>Category</label>
                <select class="ui search dropdown">
                </select>
            </div>
            <div class="field">
                <label>Text</label>
                <textarea id="new-page-editor" name="content" value="">{!! html_entity_decode( old('content') ) !!}</textarea>
            </div>
            <button class="ui primary button" tabindex="0">{{ __('Save Page') }}</button>
        </div>
    </div>
    </form>
</x-app-layout>
