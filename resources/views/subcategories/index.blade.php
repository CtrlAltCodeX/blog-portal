@extends('layouts.master')

@section('title', __('Sub-Categories'))

@section('content')
<div class="main-container container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="page-header my-0 w-100 d-flex justify-content-between align-items-center">
                <h1 class="page-title">{{ __('Sub-Categories List') }}</h1>
                <a href="{{ route('subcategories.create') }}" class="btn btn-primary">Add Sub-Category</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sub-Category Name</th>
                        <th>Category</th>
                        <th>Limit</th>
                        <th>Preference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subcategories as $subcategory)
                    <tr>
                        <td>{{ $subcategory->id }}</td>
                        <td>{{ $subcategory->name }}</td>
                        <td>{{ $subcategory->parent?->name ?? '—' }}</td>
                        <td>{{ $subcategory->category_limit ?? '-' }}</td>
                        <td>
                            @switch($subcategory->preference)
                                @case(1)
                                    {{ 'High' }}
                                    @break
                                @case(2)
                                    {{ 'Medium' }}
                                    @break
                                @case(3)
                                    {{ 'Low' }}
                                    @break
                            
                                @default
                                    
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this sub-category?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No sub-categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {!! $subcategories->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>
@endsection
