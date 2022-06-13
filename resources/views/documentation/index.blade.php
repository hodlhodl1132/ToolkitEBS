<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentation') }}
        </h2>
    </x-slot>

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
            <button class="ui right labeled icon button">
                <i class="right file icon"></i>
                New
            </button>
        </a>
        @endcan

        <div class="ui relaxed divided list">
            @foreach ($pages as $page)
            <div class="item">
                <i class="large file alternate middle aligned icon"></i>
                <div class="content">
                <a href="{{ route('documentation.show', ['slug'=> $page->slug]) }}" class="header">{{ $page->title }}</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
