@extends('layouts.master')

@section('title', __('Request New Listings (On Demand)'))

@section('content')
<div class="main-container container-fluid">
    <!--<div class="page-header">-->
    <!--    <h1 class="page-title">{{ __('On Demand Listing - Bulk Upload') }}</h1>-->
    <!--</div>-->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Request New Listings (On Demand)') }}</h3>
                </div>
                <div class="card-body">


                    <form action="{{ route('on-demand.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Requested Type') }}<span class='text-danger'>*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="Create">Create New Listings</option>
                                    <option value="Update">Update Existing Listings</option>
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
