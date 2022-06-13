<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->title }}
        </h2>
    </x-slot>

    <div class="container">
        @can('pages.edit')
        <a href="{{ route('documentation.edit', ['slug' => $page->slug]) }}">
            <button class="ui right labeled icon button">
                <i class="edit icon"></i>
                Edit
            </button>
        </a>
        <form method="POST" action="{{ route('documentation.delete', ['slug' => $page->slug]) }}">
        @csrf
        <a :href="{{ route('documentation.delete', ['slug' => $page->slug]) }}">
            <button onclick="event.preventDefault();
                        this.closest('form').submit();" class="ui right labeled icon button">
                <i class="trash alternate icon"></i>
                Delete
            </button>
        </a>
        @endcan
        </form>

        {!! html_entity_decode( $page->content ) !!}
    </div>
</x-app-layout>
