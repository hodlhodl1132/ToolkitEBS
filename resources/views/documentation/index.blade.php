<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentation') }}
        </h2>
    </x-slot>

    <div class="ui breadcrumb">
        <a href="{{ route('home') }}" class="section">Home</a>
        <div class="divider">/</div>
        <a href="{{ route('documentation.index') }}" class="section">Documentation</a>
    </div>

    @if (session('status'))
        <div class="ui success message">
            <i class="close icon"></i>
              <div class="header">
                {{ session('status') }}
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        @can('pages.edit')
        <a href="{{ route('documentation.create') }}">
            <button class="ui primary right labeled icon button">
                <i class="right file icon"></i>
                New
            </button>
        </a>
        <a href="{{ route('pagecategories.index') }}">
            <button class="ui secondary right labeled icon button">
                <i class="right folder icon"></i>
                Categories
            </button>
        </a>
        @endcan

        @foreach ($categories as $category)
        <h2 class="ui header">{{ ucfirst( $category->title ) }}</h2>
        <div class="ui relaxed divided list">
            @foreach ($category->pages()->where('deleted', false)->get() as $page)
            <div class="item">
                <i class="large file alternate middle aligned icon"></i>
                <div class="content">
                <a href="{{ route('documentation.show', ['slug'=> $page->slug, 'category_name' => $category->title]) }}" class="header">{{ $page->title }}</a>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</x-app-layout>
