@extends('layouts.master')

@section('title', __('Create Category'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Category') }}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Add New Category</h4>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            {{-- Category Name --}}
                            <div class="form-group col-4">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter category name" required>
                            </div>

                            {{-- Category Limit --}}
                            {{-- <div class="form-group col-4">
                                <label for="category_limit" class="form-label">Category Limit</label>
                                <input type="number" class="form-control" name="category_limit" placeholder="Enter limit">
                            </div> --}}

                            {{-- Preference --}}
                            {{-- <div class="form-group col-4">
                                <label for="preference" class="form-label">Preference</label>
                                <select class="form-control" name="preference">
                                    <option value="">Select Preference</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
