@extends('layouts.master')

@section('title', __('Create Sub-Category'))

@section('content')
<div class="main-container container-fluid">

    <div class="page-header">
        <h1 class="page-title">{{ __('Create Sub-Category') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sub-subcategories.index') }}">{{ __('Sub-Categories') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Create Sub-Category') }}</li>
            </ol>
        </div>
    </div>

    <form action="{{ route('sub-subcategories.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">{{ __('Add Sub-Category') }}</h4>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="name">{{ __('Sub-Sub-Category Name') }}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="form-group col-4">
                                <label for="parent_id">{{ __('Select Category') }}<span class="text-danger">*</span></label>
                                <select name="parent_id" class="form-control" required id='category_id'>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-4">
                                <label for="parent_id">{{ __('Select Sub Category') }}<span class="text-danger">*</span></label>
                                <select name="sub_parent_id" class="form-control" required id='sub_category_id'>
                                    <option value="">-- Select Sub Category --</option>
                                </select>
                            </div>

                            <div class="form-group col-4">
                                <label for="category_limit">{{ __('Limit') }}</label>
                                <input type="number" class="form-control" name="category_limit">
                            </div>

                            <div class="form-group col-4">
                                <label for="preference">{{ __('Preference') }}</label>
                                <select name="preference" class="form-control">
                                    <option value="">Select Preference</option>
                                    @foreach (preferences() as $key => $preferences)
                                        <option value="{{ $key }}">{{ $preferences }}</option>
                                    @endforeach
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

@push('js')

@include('posts-script')

@endpush