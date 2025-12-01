@extends('layouts.master')

@section('title', __('Edit Page'))

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Edit Page</h3>
        <a href="{{ route('pages.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card-body">

        <form action="{{ route('pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Title *</label>
                    <input type="text" name="title" value="{{ $page->title }}" class="form-control" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description *</label>
                    <textarea name="description" rows="4" class="form-control">{{ $page->description }}</textarea>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Category *</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $page->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Sub Category *</label>
                    <select name="sub_category_id" id="sub_category_id" class="form-control" required>
                        <option value="{{ $page->sub_category_id }}">
                            {{ $page->subCategory->name ?? 'Selected' }}
                        </option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Sub Sub Category *</label>
                    <select name="sub_sub_category_id" id="sub_sub_category_id" class="form-control" required>
                        <option value="{{ $page->sub_sub_category_id }}">
                            {{ $page->subSubCategory->name ?? 'Selected' }}
                        </option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Status *</label>
                    <select name="status" class="form-control">
                        <option value="open" {{ $page->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ $page->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

            </div>

            <button class="btn btn-success mt-2">Update Page</button>

        </form>

    </div>
</div>

@endsection

@push('js')
@include('posts-script');

@endpush