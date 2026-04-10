@extends('layouts.master')

@section('title', 'Create Complaint User')

@section('content')

<div class="card">
    <div class='card-header'>
        <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
            <h1 class="page-title">Create Complaint User</h1>
            <a href="{{ route('complaints-user.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('complaints-user.store') }}" method="POST">
            @csrf
            <div class='row'>
                <div class='col-md-4'>
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class='col-md-4'>
                    <div class="form-group mb-3">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class='col-md-4'>
                    <div class="form-group mb-3">
                        <label for="phone">Phone No</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>

@endsection