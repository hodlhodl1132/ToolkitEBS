<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->title }}
        </h2>
    </x-slot>

    <div class="ui breadcrumb">
        <a href="{{ route('home') }}" class="section">Home</a>
        <div class="divider">/</div>
        <a href="{{ route('documentation.index') }}" class="section">Documentation</a>
        <div class="divider">/</div>
        <a href="{{ route('documentation.index') }}" class="section">{{ ucfirst( $page_category->title ) }}</a>
        <div class="divider">/</div>
        <a href="{{ route('documentation.show', ['slug' => $page->slug, 'category_name' => $page_category->title]) }}" class="section">{{ $page->title }}</a>
    </div>

    <div class="container">
        @can('pages.edit')
        <a href="{{ route('documentation.edit', ['slug' => $page->slug]) }}">
            <button class="ui grey right labeled icon button">
                <i class="edit icon"></i>
                Edit
            </button>
        </a>
        @endcan
        @can('pages.delete')
        <form method="POST" action="{{ route('documentation.delete', ['slug' => $page->slug]) }}">
        @csrf
        <a :href="{{ route('documentation.delete', ['slug' => $page->slug]) }}">
            <button onclick="event.preventDefault();
                        this.closest('form').submit();" class="ui red right labeled icon button">
                <i class="trash alternate icon"></i>
                Delete
            </button>
        </a>
        </form>
        @endcan

        {!! html_entity_decode( $page->content ) !!}
    </div>
</x-app-layout>
