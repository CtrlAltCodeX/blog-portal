@extends('layouts.master')

@section('title', __('Create Page List'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title">Create Page List</h1>
        <a href="{{ route('createpages.create') }}" class="btn btn-primary">+ New Entry</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch ID</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Sub-Category</th>
                        <th>Preferred Date</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $page->batch_id }}</td>
                        <td>{{ $page->user->name ?? 'N/A' }}</td>
                        <td>{{ $page->category->name ?? 'N/A' }}</td>
                        <td>{{ $page->subCategory->name ?? 'N/A' }}</td>
                        <td>{{ $page->any_preferred_date }}</td>
                        <td>{{ $page->date ?? '-' }}</td>
                        <td>{{ ucfirst($page->status) }}</td>
                        <td>
                            @if($page->upload)
                                <a href="{{ asset('storage/' . $page->upload) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
             {{-- Pagination links --}}
            <div class="d-flex justify-content-center mt-3">
                {!! $pages->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>
@endsection
