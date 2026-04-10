@extends('layouts.master')

@section('title', 'Complaint User')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Complaint Users</h1>
            <a href="{{ route('complaints-user.create') }}" class="btn btn-primary">Create Complaint User</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @forelse($complaints as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->phone }}</td>
                    <td>
                        <a href="{{ route('complaints-user.edit', $row->id) }}"
                            class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('complaints-user.destroy', $row->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No Complaint Users Found.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection