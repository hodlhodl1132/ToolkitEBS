<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Page Categories') }}
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

    @can('pages.edit')
    <a href="{{ route('pagecategories.create') }}">
        <button class="ui primary right labeled icon button">
            <i class="right folder icon"></i>
            New
        </button>
    </a>
    @endcan

    <table class="ui celled table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Total Pages</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pageCategories as $cat)
                <tr>
                    <td>{{ $cat->title }}</td>
                    <td>{{ count( $cat->pages ) }}</td>
                    <td>
                        <a href="{{ route('pagecategories.edit', ['id' => $cat->id]) }}">
                            <button class="ui button secondary">
                                Edit
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-app-layout>
