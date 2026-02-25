@extends('layouts.master')

@section('title', 'Edit Department')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Edit Department</h1>
            <a href="{{ route('departments.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="name">Department Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="head">Department Head</label>
                <input type="text" name="head" id="head" class="form-control" value="{{ old('head', $department->head) }}">
            </div>

            <div class="form-group mb-3">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $department->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone">Phone Nos'</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $department->phone) }}">
            </div>

            <div class="form-group mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $department->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $department->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

@endsection
