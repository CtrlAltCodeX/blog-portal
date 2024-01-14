@extends('layouts.master')

@section('title', __('Create Listing'))

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.2/tinymce.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    tinymce.init({
        selector: 'textarea#description',
        menubar: false,
        plugins: 'image media wordcount save fullscreen code table lists link',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor image alignleft aligncenter alignright alignjustify | link hr |numlist bullist outdent indent  | removeformat | code | table | aibutton',
        image_advtab: true,
        valid_elements: '*[*]',
        relative_urls: false,
        remove_script_host: false,
        document_base_url: '{{ asset(' / ') }}',
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            let xhr, formData;

            xhr = new XMLHttpRequest();

            xhr.withCredentials = false;

            xhr.open('POST', '{{ route("tinymce.upload") }}');

            xhr.upload.onprogress = ((e) => progress((e.loaded / e.total) * 100));

            xhr.onload = function() {
                let json;

                if (xhr.status === 403) {
                    reject("http-error", {
                        remove: true
                    });

                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    reject("http-error");

                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    reject("invalid-json" + xhr.responseText);

                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = (() => reject("upload-failed"));

            formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }),

        file_picker_callback: function(cb, value, meta) {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function() {
                let file = this.files[0];

                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function() {
                    let id = 'blobid' + new Date().getTime();
                    let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    let base64 = reader.result.split(',')[1];
                    let blobInfo = blobCache.create(id, file, base64);

                    blobCache.add(blobInfo);

                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
            };

            input.click();
        },
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
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create Listing') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Title*') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title" autofocus placeholder="Title">

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('Description*') }}</label>
                                <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" autofocus placeholder="Description" rows="10">{{ old('description') }}</textarea>

                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div id="fileInputContainer">
                                <div class="form-group">
                                    <label for="fileInput1">Images*</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control-file @error('images') is-invalid @enderror" id="fileInput1" name="images[]">

                                        @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button id="addFileInput" type="button" class="btn btn-primary">Add File Input</button>
                        </div>
                    </div>
                </div> -->

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="selling_price" class="form-label">{{ __('Selling Price*') }}</label>
                                <input id="selling_price" type="number" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') }}" autocomplete="selling_price" autofocus placeholder="Selling Price">

                                @error('selling_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="mrp" class="form-label">{{ __('MRP*') }}</label>
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') }}" autocomplete="mrp" autofocus placeholder="MRP">

                                @error('mrp')
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
                                <label for="publication" class="form-label">{{ __('Publication*') }}</label>
                                <input id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') }}" autocomplete="publication" autofocus placeholder="Publication">

                                @error('publication')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="author_name" class="form-label">{{ __('Author Name*') }}</label>
                                <input id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') }}" autocomplete="author_name" autofocus placeholder="Author name">

                                @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                <input id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') }}" autocomplete="edition" autofocus placeholder="Edition">

                                @error('edition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="about_author" class="form-label">{{ __('About Author*') }}</label>
                                <textarea id="about_author" class="form-control @error('about_author') is-invalid @enderror" name="about_author" autocomplete="about_author" autofocus placeholder="About Author Name" rows="5">{{ old('about_author') }}</textarea>

                                @error('about_author')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="search_key" class="form-label">{{ __('Search Key*') }}</label>
                            <textarea id="search_key" class="form-control @error('search_key') is-invalid @enderror" name="search_key" autocomplete="search_key" autofocus placeholder="Search Key" rows="5">{{ old('search_key') }}</textarea>

                            @error('search_key')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="label" class="form-label">{{ __('Label*') }}</label>
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
                            <div class="form-group col-md-6">
                                <label for="sku" class="form-label">{{ __('SKU*') }}</label>
                                <input id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') }}" autocomplete="sku" autofocus placeholder="SKU">

                                @error('sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="medium" class="form-label">{{ __('Medium') }}</label>
                                <input id="medium" type="text" class="form-control @error('medium') is-invalid @enderror" name="medium" value="{{ old('medium') }}" autocomplete="medium" autofocus placeholder="Medium">

                                @error('medium')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                                <input id="pages" type="number" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') }}" autocomplete="pages" autofocus placeholder="No. of Pages">

                                @error('pages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="weight" class="form-label">{{ __('Weight') }}</label>
                                <input id="weight" type="text" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ old('weight') }}" autocomplete="weight" autofocus placeholder="Weight">

                                @error('weight')
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
                                <label for="country_origin" class="form-label">{{ __('Country of Origin*') }}</label>
                                <input id="country_origin" type="text" class="form-control @error('country_origin') is-invalid @enderror" name="country_origin" value="{{ old('country_origin') ?? 'India' }}" autocomplete="country_origin" autofocus placeholder="Country of Origin">

                                @error('country_origin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="language" class="form-label">{{ __('Language*') }}</label>
                                <input id="language" type="text" class="form-control @error('language') is-invalid @enderror" name="language" value="{{ old('language') }}" autocomplete="language" autofocus placeholder="Language">

                                @error('language')
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
                                <label for="isbn_10" class="form-label">{{ __('ISBN 10') }}</label>
                                <input id="isbn_10" type="text" class="form-control @error('isbn_10') is-invalid @enderror" name="isbn_10" value="{{ old('isbn_10') }}" autocomplete="isbn_10" autofocus placeholder="ISBN 10">

                                @error('isbn_10')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="isbn_13" class="form-label">{{ __('ISBN 13') }}</label>
                                <input id="isbn_13" type="text" class="form-control @error('isbn_13') is-invalid @enderror" name="isbn_13" value="{{ old('isbn_13') }}" autocomplete="isbn_13" autofocus placeholder="ISBN 13">

                                @error('isbn_13')
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
                                    <label for="fileInput1">Images*</label>
                                    <div class="row mb-5">
                                        <div class="col-lg-12 col-sm-12 mb-4">
                                            <input type="file" class="dropify" data-bs-height="180" id="fileInput1" name="images[]" />
                                        </div>
                                    </div>
                                    <!-- <div class="form-group mb-0">
                                        <input id="demo" type="file" name="multipleImages[]" multiple>
                                    </div> -->
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
<script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#draft").click(function(e) {
            e.preventDefault();

            $("#form").append("<input type='hidden' name='isDraft' value=1 />");

            $("#form").submit();
        })
    })
</script>
@endpush