<x-app-layout>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#new-page-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
            extended_valid_elements: '#i[class]'
        });
    </script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->title }}
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

    <form method="POST" action="{{ route('documentation.update', ['slug' => $page->slug]) }}">
    @csrf
    <input type="hidden" name="id" value="{{ old('id', $page->id) }}">
    <div class="container">
        <div class="ui form">
            <div class="field">
                <label>Page Title</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}">
            </div>
            <div class="field">
                <label>Category</label>
                <select class="ui search dropdown" name="page_category">
                    @foreach ($pageCategories as $category)
                        <option 
                        {{ $page->category_id == $category->id ? 'selected' : '' }}
                        value="{{ $category->id }}">
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Text</label>
                <textarea id="new-page-editor" name="content">{!! html_entity_decode( old('content', $page->content) ) !!}</textarea>
            </div>
            <button class="ui primary button" tabindex="0">{{ __('Save Page') }}</button>
        </div>
    </div>
    </form>
</x-app-layout>
