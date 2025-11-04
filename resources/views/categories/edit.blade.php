@extends('layouts.master')

@section('title', __('Edit Category'))

@section('content')
<div class="main-container container-fluid">

    <div class="page-header">
        <h1 class="page-title">{{ __('Edit Category') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('Categories') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Edit Category') }}</li>
            </ol>
        </div>
    </div>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">{{ __('Update Category') }}</h4>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            {{-- Category Name --}}
                            <div class="form-group col-4">
                                <label for="name" class="form-label">{{ __('Category Name') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control"
                                    name="name"
                                    value="{{ old('name', $category->name) }}"
                                    placeholder="Enter category name"
                                    required>
                            </div>

                            {{-- Parent Category --}}
                            <div class="form-group col-4">
                                <label for="parent_id" class="form-label">{{ __('Parent Category') }}</label>
                                <select class="form-control" name="parent_id">
                                    <option value="">-- None (Parent Category) --</option>
                                    {!! renderCategoryOptions($categories, '', $category->parent_id) !!}
                                </select>
                            </div>

                            {{-- Category Limit --}}
                            <div class="form-group col-2">
                                <label for="category_limit" class="form-label">{{ __('Category Limit') }}</label>
                                <input type="number"
                                    class="form-control"
                                    name="category_limit"
                                    value="{{ old('category_limit', $category->category_limit) }}"
                                    placeholder="Limit">
                            </div>

                            {{-- Preference --}}
                            <div class="form-group col-2">
                                <label for="preference" class="form-label">{{ __('Preference') }}</label>
                                <select class="form-control" name="preference">
                                    <option value="">Select Preference</option>
                                    <option value="High" {{ old('preference', $category->preference) == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Medium" {{ old('preference', $category->preference) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="Low" {{ old('preference', $category->preference) == 'Low' ? 'selected' : '' }}>Low</option>
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