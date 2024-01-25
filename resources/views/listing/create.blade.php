@extends('layouts.master')

@section('title', __('Create Listing'))

@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#desc').summernote();
    });
</script>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Counter to keep track of the number of file input fields
        var fileInputCounter = 2; // Start from 2 since the first one is already visible

        // Function to add a new file input field
        function addFileInput() {
            var fileInputHtml = '<div class="form-group">' +
                '<div class="input-group">' +
                '<input type="file" class="form-control-file" id="fileInput' + fileInputCounter + '" name="images[]">' +
                '<div class="input-group-append pt-2">' +
                '<button class="btn btn-danger btn-sm removeFileInput">Remove</button>' +
                '</div>' +
                '</div>' +
                '</div>';

            // Append the new file input field to the container
            $("#fileInputContainer").append(fileInputHtml);

            // Enable the remove button for subsequent file input fields
            $("#fileInputContainer .form-group:not(:first-child) .removeFileInput").prop('disabled', false);

            // Increment the counter for the next file input field
            fileInputCounter++;
        }

        // Attach a click event to the "Add File Input" button
        $("#addFileInput").click(function() {
            addFileInput();
        });

        // Attach a click event to dynamically added "Remove" buttons
        $("#fileInputContainer").on("click", ".removeFileInput", function() {
            // Check if there's more than one file input field before removing
            if ($("#fileInputContainer .form-group").length > 1) {
                $(this).closest('.form-group').remove();
            }
        });
    });
</script>
@endpush

@section('content')

<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Listing') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Listing') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Create Listing') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('listing.store') }}" method="POST" enctype='multipart/form-data' id='form'>
        @csrf
        <div class="row">
            <div class="col-md-9 col-xl-9">
                <div class="card">
                    <!-- <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create Listing') }}
                        </h4>
                    </div> -->

                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Title') }}<span class="text-danger">*</span> <span class="text-success">(Prduct Name | Author | Edition | Publication ( Medium ) )</span></label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title" autofocus placeholder="Title">
                                <span class="error-message title" style="color:red;"></span>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('Description') }}<span class="text-danger">*</span><span class="text-danger"> ( Enter Detail Description without using 3rd party link)</span></label>
                                <!-- <div id="summernote" id="description" class="form-control @error('description') is-invalid @enderror" name="description">
                                    {{ old('description') }}
                                </div> -->

                                <textarea id="desc" type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" autofocus placeholder="Description" rows="10">{{ old('description') }}</textarea>
                                <span class="error-message description" style="color:red;"></span>

                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail Price)</span></label>
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') }}" autocomplete="mrp" autofocus placeholder="MRP">
                                <span class="error-message mrp" style="color:red;"></span>

                                @error('mrp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="selling_price" class="form-label">{{ __('Selling Price') }}<span class="text-danger">*</span></label>
                                <input id="selling_price" type="number" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') }}" autocomplete="selling_price" autofocus placeholder="Selling Price">
                                <span class="error-message selling_price" style="color:red;"></span>

                                @error('selling_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="publication" class="form-label">{{ __('Publisher') }}<span class="text-danger">*</span></label>
                                <input id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') }}" autocomplete="publication" autofocus placeholder="Publisher">
                                <span class="error-message publication" style="color:red;"></span>

                                @error('publication')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="author_name" class="form-label">{{ __('Author Name') }}<span class="text-danger">*</span></label>
                                <input id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') }}" autocomplete="author_name" autofocus placeholder="Author name">
                                <span class="error-message author_name" style="color:red;"></span>

                                @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                <input id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') }}" autocomplete="edition" autofocus placeholder="Edition">
                                <span class="error-message edition" style="color:red;"></span>

                                @error('edition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="label" class="form-label">{{ __('Category') }}<span class="text-danger">*</span><span class="text-danger"> ( Publication, 1 Category, 1 Tag, Others ) </span></label>
                            <select class="form-control select2  @error('label') is-invalid @enderror" data-placeholder="Choose Label" multiple name="label[]">
                                @foreach($categories as $category)
                                <option value="{{ $category['term'] }}" {{ $category['term'] == 'Product' ? 'selected' : '' }}>
                                    {{$category['term']}}
                                </option>
                                @endforeach
                            </select>

                            @error('label')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="sku" class="form-label">{{ __('SKU') }}<span class="text-danger">*</span><span class="text-danger"> ( Short Code ) </span></label>
                                <input id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') }}" autocomplete="sku" autofocus placeholder="SKU">
                                <span class="error-message sku" style="color:red;"></span>

                                @error('sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="language" class="form-label">{{ __('Language') }}<span class="text-danger">*</span></label>
                                <input id="language" type="text" class="form-control @error('language') is-invalid @enderror" name="language" value="{{ old('language') }}" autocomplete="language" autofocus placeholder="Language">
                                <span class="error-message language" style="color:red;"></span>

                                @error('language')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                                <input id="pages" type="text" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') }}" autocomplete="pages" autofocus placeholder="No. of Pages">
                                <span class="error-message pages" style="color:red;"></span>

                                @error('pages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="binding" class="form-label">{{ __('Binding Type') }}<span class="text-danger">*</span></label>
                                <input id="binding" type="text" class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') }}" autocomplete="binding" autofocus placeholder="Binding Type">
                                <span class="error-message binding" style="color:red;"></span>

                                @error('binding')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="condition" class="form-label">{{ __('Condition') }}<span class="text-danger">*</span></label>
                                <input id="condition" type="text" class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}" autocomplete="condition" autofocus placeholder="Condition">
                                <span class="error-message condition" style="color:red;"></span>

                                @error('condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Insta Mojo URL') }}</label>
                                <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" autocomplete="url" autofocus placeholder="Insta Mojo url">
                                <span class="error-message url" style="color:red;"></span>

                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-primary float-right">Publish</button>
                            <button type="submit" class="btn btn-primary float-right" id='draft'>Save as Draft</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div id="fileInputContainer">
                                <div class="form-group">
                                    <label for="fileInput1">Main Images<span class="text-danger">*</span></label>

                                    <div class="form-group mb-0" @error('images') style="border: red 2px dotted;" @enderror>
                                        <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="fileInput1" name="images[]" />
                                    </div>

                                    @error('images')
                                    <span class="invalid-feedback mt-2" style="display:block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label for="fileInput1">Additional Images<span class="text-danger">*</span></label>
                                    <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                        <input id="demo" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                    </div>

                                    @error('multipleImages')
                                    <span class="invalid-feedback mt-2" style="display:block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End Row -->
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- INTERNAL File-Uploads Js-->
<script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#draft").click(function(e) {
            e.preventDefault();

            $("#form").append("<input type='hidden' name='isDraft' value=1 />");

            $("#form").submit();
        });

        $('#form').submit(function(event) {
            // Reset previous error messages
            $('.error-message').text('');

            // Flag to check if any URL is found
            var valid = true;
            var requiredvalid = true;

            // Iterate over each input field with the class 'no-url-validation'
            $('input').each(function() {
                var inputValue = $(this).val();
                var urlRegex = /^(http|https):\/\/[^\s]*$/i;

                if (urlRegex.test(inputValue) &&
                    inputValue != 'http://' &&
                    inputValue != 'url'
                ) {
                    // Display error message
                    var fieldId = $(this).attr('name');
                    if (fieldId != 'images[]' && fieldId != 'multipleImages[]') {
                        $('.' + fieldId).text('Please do not enter URLs.');
                        valid = false;
                    }
                }

                if (inputValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId != 'images[]' &&
                        fieldId != 'multipleImages[]' &&
                        fieldId != 'files' &&
                        fieldId
                    ) {
                        $('.' + fieldId).text('This field is required');

                        requiredvalid = false;
                    }
                }
            });

            $('textarea').each(function() {
                var textareaValue = $(this).val();
                var urlRegex = /^(http|https):\/\/[^\s]*$/i;

                if (urlRegex.test(textareaValue)) {
                    // Display error message
                    var fieldId = $(this).attr('name');
                    $('.' + fieldId).text('Please do not enter URLs.');
                    valid = false;
                }

                if (textareaValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId) {
                        $('.' + fieldId).text('This field is required');

                        requiredvalid = false;
                    }
                }
            });

            var url = $('#url').val();

            if (!url.includes('https://www.instamojo.com/EXAM360/')) {
                $('.url').text('Please add instamojo link');
                valid = false;
            } else {
                $('.url').text('');
                valid = true;
            }

            // Prevent form submission if a URL is found
            if (!valid || !requiredvalid) {
                event.preventDefault();
            }
        });
    })
</script>
@endpush