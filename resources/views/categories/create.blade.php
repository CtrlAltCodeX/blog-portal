@extends('layouts.master')

@section('title', __('Create Category'))

@section('content')
<div class="main-container container-fluid">

    <div class="page-header">
        <h1 class="page-title">{{ __('Create Category') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('Categories') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Create Category') }}</li>
            </ol>
        </div>
    </div>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">{{ __('Add Category') }}</h4>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="name" class="form-label">{{ __('Category Name') }}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter category name" required>
                            </div>

                            <div class="form-group col-4">
                                <label for="parent_id" class="form-label">{{ __('Parent Category') }}</label>
                                <select class="form-control" name="parent_id">
                                    <option value="">-- None (Parent Category) --</option>
                                    {!! renderCategoryOptions($categories) !!}
                                </select>


                            </div>

                            <div class="form-group col-2">
                                <label for="category_limit" class="form-label">{{ __('Category Limit') }}</label>
                                <input type="number" class="form-control" name="category_limit" placeholder="Limit">
                            </div>

                            <div class="form-group col-2">
                                <label for="preference" class="form-label">{{ __('Preference') }}</label>
                                <select class="form-control" name="preference">
                                    <option value="">Select Preference</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection