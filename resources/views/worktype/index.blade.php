@extends('layouts.master')

@section('title', 'Work Type & Value')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Work Type</h1>
            <a href="{{ route('worktype.create') }}" class="btn btn-primary">Create Work Type</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>SL</th>
                    <th>Cause</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @forelse($workTypes as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->cause }}</td>
                    <td>{{ $row->amount }}</td>
                    <td>
                        <a href="{{ route('worktype.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('worktype.destroy', $row->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No Work Types Found.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection