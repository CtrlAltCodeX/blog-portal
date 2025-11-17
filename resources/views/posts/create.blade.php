@extends('layouts.master')

@section('title', __('Create Page Entry'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Page Entry') }}</h1>
    </div>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Add New Entry</h4>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            <div class="card-body row">
                {{-- Select Category --}}
                <div class="form-group col-4">
                    <label>Select Category<span class='text-danger'>*</span></label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Select Sub-Category<span class='text-danger'>*</span></label>
                    <select class="form-control" name="sub_category_id" id="sub_category_id" required>
                        <option value="">-- Select Sub-Category --</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Select Sub Sub-Category<span class='text-danger'>*</span></label>
                    <select class="form-control" name="sub_sub_category_id" id="sub_sub_category_id" required>
                        <option value="">-- Select Sub Sub-Category --</option>
                    </select>
                </div>

                {{-- Preferred Date --}}
                <div class="form-group col-4">
                    <label>Any Preferred Date?</label>
                    <select class="form-control" name="any_preferred_date" id="preferredDateSelect">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>

                {{-- Date Field --}}
                <div class="form-group col-4" id="dateField" style="display:none;">
                    <label>Date</label>
                    <input type="date" class="form-control" name="date">
                </div>

                {{-- Upload --}}
                <div class="form-group col-4">
                    <label>Upload Attachment</label>
                    <input type="file" name="upload[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" multiple>
                </div>

                <div class="form-group col-4">
                    <label>URLs (Optional)</label>

                    <div id="url-wrapper">
                        <div class="input-group mb-2">
                            <input type="text" name="url[]" class="form-control" placeholder="https://example.com">
                            <button type="button" class="btn btn-success add-url">+</button>
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

<script>
    // Add new URL input
    $(document).on('click', '.add-url', function() {
        $('#url-wrapper').append(`
            <div class="input-group mb-2">
                <input type="text" name="url[]" class="form-control" placeholder="https://example.com">
                <button type="button" class="btn btn-danger remove-url">X</button>
            </div>
        `);
    });

    // Remove URL input
    $(document).on('click', '.remove-url', function() {
        $(this).closest('.input-group').remove();
    });
</script>

@endpush