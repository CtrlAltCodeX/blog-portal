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

    <div class="row">
        <div class="col-md-9 col-xl-9 fields">
            <form action="{{ route('listing.store') }}" method="POST" enctype='multipart/form-data' id='form'>
                @csrf
                <div class="card">
                    <!-- <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create Listing') }}
                        </h4>
                    </div> -->

                    <div class="card-body">
                        <div id="progressBar" class="text-end"></div>

                        <div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Product Title') }}<span class="text-danger">*</span> <span class="text-success">(Product Name | Author | Edition | Publication ( Medium ) )</span></label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title" autofocus placeholder="Title">
                                <span class="error-message title" style="color:red;"></span>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Product Description') }}<span class="text-danger">*</span><span class="text-danger"> ( Enter Detail Description without using 3rd party link) </span></div><a target='_blank' href="https://chat.openai.com">ChatGPT</a>
                                </label>
                                <!-- <div id="summernote" id="description" class="form-control @error('description') is-invalid @enderror" name="description">
                                    {{ old('description') }}
                                </div> -->

                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" autofocus placeholder="Description" rows="10">{{ old('description') }}</textarea>
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
                                <label for="condition" class="form-label">{{ __('Condition') }}<span class="text-danger">*</span></label>
                                <select class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}">
                                    <option value="">--Select--</option>
                                    <option value="New">New</option>
                                    <option value="Like New">Like New</option>
                                    <option value="Old">Old</option>
                                </select>
                                <!-- <input id="condition" type="text" class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}" autocomplete="condition" autofocus placeholder="Binding Type"> -->
                                <span class="error-message condition" style="color:red;"></span>

                                @error('condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="binding" class="form-label">{{ __('Binding Type') }}<span class="text-danger">*</span></label>
                                <select class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') }}">
                                    <option value="">--Select--</option>
                                    <option value="Hardcover">Hardcover</option>
                                    <option value="Paperback">Paperback</option>
                                </select>
                                <!-- <input id="binding" type="text" class="form-control @error('binding') is-invalid @enderror" name="binding" value="{{ old('binding') }}" autocomplete="binding" autofocus placeholder="Condition"> -->
                                <span class="error-message binding" style="color:red;"></span>
                                @error('binding')
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

                        <div class="row" id="addUrls">
                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Main Image URL') }}</label>
                                <input id="url" type="text" class="form-control @error('images') is-invalid @enderror" name="images[]" autocomplete="images" autofocus placeholder="Base URL">
                                <span class="error-message images[]" style="color:red;"></span>

                                @error('images')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <button type='button' id='addFileInput' class="btn btn-primary">Add More Images</button>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-warning float-right" id='draft'>Save as Draft</button>
                            <button type="submit" class="btn btn-success float-right">Publish to Website</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div id="fileInputContainer">
                            <div class="form-group"></div>
                            <label for="fileInput1" class="mt-2">Images<span class="text-danger">*</span>( Multiple Images )</label>
                            <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                <form action="{{ route('convert.image') }}" method="post" enctype="multipart/form-data" id='multipleImagesform'>
                                    @csrf
                                    <input id="multipleFiles" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                    <div id='multiImagesDownload' style="display: none;">
                                        <a href='#' class="w-100 d-flex justify-content-end my-4" id='downloadMultipleImage'>
                                            <img src="/downlod-icon.png" />
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

@include('listing.script')

<script>
    $(document).ready(function() {
        $('#fileInput1').change(function() {
            $("#singleImageDownload").show();
        })

        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        })

        $('#downloadImage').click(function() {
            $('#singleImageForm').submit();
        })

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        })
    })
</script>
@endpush