@extends('layouts.master')

@section('title', 'Edit Issue Type')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Edit Issue Type</h1>
            <a href="{{ route('issue-types.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('issue-types.update', $issueType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="name">Issue Type Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $issueType->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $issueType->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $issueType->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

@endsection
