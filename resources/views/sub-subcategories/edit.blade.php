@extends('layouts.master')

@section('title', __('Edit Sub-Category'))

@section('content')
<div class="main-container container-fluid">

    <div class="page-header">
        <h1 class="page-title">{{ __('Edit Sub-Category') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sub-subcategories.index') }}">{{ __('Sub-Categories') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit Sub-Category') }}</li>
            </ol>
        </div>
    </div>

    <form action="{{ route('sub-subcategories.update', $subcategory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">{{ __('Update Sub-Category') }}</h4>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- Sub-Category Name --}}
                            <div class="form-group col-4">
                                <label for="name" class="form-label">{{ __('Sub-Category Name') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    name="name"
                                    value="{{ old('name', $subcategory->name) }}"
                                    placeholder="Enter sub-category name"
                                    required>
                            </div>

                            {{-- Select Parent Category --}}
                            <div class="form-group col-4">
                                <label for="parent_id" class="form-label">{{ __('Select Category') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="parent_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $subcategory->parent_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category Limit --}}
                            <div class="form-group col-2">
                                <label for="category_limit" class="form-label">{{ __('Limit') }}</label>
                                <input type="number"
                                    class="form-control"
                                    name="category_limit"
                                    value="{{ old('category_limit', $subcategory->category_limit) }}"
                                    placeholder="Limit">
                            </div>

                            {{-- Preference --}}
                            <div class="form-group col-2">
                                <label for="preference" class="form-label">{{ __('Preference') }}</label>
                                <select class="form-control" name="preference">
                                    <option value="">Select Preference</option>
                                    @foreach (preferences() as $key => $preferences)
                                        <option value="{{ $key }}" {{ old('preference', $subcategory->preference) == $key ? 'selected' : '' }}>{{ $preferences }}</option>
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
