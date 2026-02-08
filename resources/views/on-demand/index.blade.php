@extends('layouts.master')

@section('title', __('On Demand Listing - Bulk Upload'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('On Demand Listing - Bulk Upload') }}</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Upload Multiple Images') }}</h3>
                </div>
                <div class="card-body">


                    <form action="{{ route('on-demand.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Select Category') }}</label>
                                <select name="category" class="form-control" required>
                                    <option value="Create">Request To Create</option>
                                    <option value="Update">Request To Update</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Select Images') }}</label>
                                <input type="file" name="images[]" class="form-control" multiple required accept="image/*">
                                <small class="text-muted">You can select multiple images at once.</small>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Upload & Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
