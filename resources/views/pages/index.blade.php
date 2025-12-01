@extends('layouts.master')

@section('title', __('Page Listing'))

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Page Listing</h3>

        <a href="{{ route('pages.create') }}" class="btn btn-primary">
            + Create Page
        </a>
    </div>

    <div class="card-body">

        {{-- FILTER SECTION --}}
        <div class='row'>
            <form method="GET" action="{{ route('pages.index') }}" id="filterForm">

                <div class="row mb-3">
                    <div class="col-md-2">
                        <label>Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">All</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" 
                                    {{ $category_id==$cat->id?'selected':'' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Sub Category</label>
                        <select class="form-control" name="sub_category_id" id="sub_category_id">
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Sub-Sub Category</label>
                        <select class="form-control" name="sub_sub_category_id" id="sub_sub_category_id">
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end mt-2">
                        <button type="submit" class="btn btn-primary btn-block w-100">Filter</button>
                    </div>
                </div>

            </form>
        </div>

        {{-- PAGE LIST TABLE --}}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Batch ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Sub-Sub Category</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Created Time</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pages as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>

                    <td>{{ $row->batch_id }}</td>

                    <td>{{ $row->title }}</td>

                    <td>{{ optional($row->category)->name }}</td>
                    <td>{{ optional($row->subCategory)->name }}</td>
                    <td>{{ optional($row->subSubCategory)->name }}</td>

                    <td>
                        <span >{{ $row->status }}</span>
                    </td>

                    <td>{{ optional($row->creator)->name }}</td>

                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                    <td>{{ $row->created_at->format('h:i A') }}</td>

                    <td>
                        <a href="{{ route('pages.edit', $row->id) }}" 
                           class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form action="{{ route('pages.destroy', $row->id) }}" 
                              method="POST" 
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this page?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="11" align="center">No Pages Found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3">
            {!! $pages->links('pagination::bootstrap-5') !!}
        </div>

    </div>
</div>

@endsection
@push('js')
@include('posts-script');

@endpush