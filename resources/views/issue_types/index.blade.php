@extends('layouts.master')

@section('title', 'Issue Types')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Issue Types</h1>
            <a href="{{ route('issue-types.create') }}" class="btn btn-primary">Create Issue Type</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @forelse($issueTypes as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @if($row->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('issue-types.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('issue-types.destroy', $row->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No Issue Types Found.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection
