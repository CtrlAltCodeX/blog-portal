@extends('layouts.master')

@section('title', 'Departments')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Departments</h1>
            <a href="{{ route('departments.create') }}" class="btn btn-primary">Create Department</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>SL</th>
                    <th>Department</th>
                    <th>Dept. Head</th>
                    <th>E-mail</th>
                    <th>Phone Nos'</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @forelse($departments as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->head }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->phone }}</td>
                    <td>
                        <a href="{{ route('departments.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('departments.destroy', $row->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No Departments Found.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection
