@extends('layouts.master')

@section('title', __('Categories'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('Categories List') }}</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary float-right">Add Category</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>Limit</th>
                        <th>Preference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent?->name ?? '—' }}</td>
                        <td>{{ $category->category_limit ?? '-' }}</td>
                        <td>{{ $category->preference ?? '-' }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center mt-3">
                {!! $categories->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>

</div>
@endsection