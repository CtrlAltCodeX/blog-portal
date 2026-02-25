@extends('layouts.master')

@section('title', 'Create Issue Type')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Create Issue Type</h1>
            <a href="{{ route('issue-types.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('issue-types.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Issue Type Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>

@endsection
