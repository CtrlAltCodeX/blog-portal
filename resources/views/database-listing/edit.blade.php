@extends('layouts.master')

@section('title', __('Update New Listing ( DB )'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Update New Listing ( DB )') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Listing') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Update New Listing ( DB )') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row">
        <div class="col-md-9 col-xl-12 fields">
            <form action="" method="POST" enctype='multipart/form-data' id='formTest'>
                @csrf
                @method('PUT')
                <input type="hidden" name="created_by" value="{{ $listing->created_by }}" />
                <input type="hidden" name="created_on" value='{{ date("Y-m-d", strtotime($listing->updated_at)) }}' />
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update New Listing ( DB )') }}
                        </h4>

                        <!-- <button type="submit" class="btn btn-primary float-right">Save</button> -->
                    </div>

                    <div class="card-body">
                        <div id="progressBar" class="text-end"></div>

                        <div>
                            <div class="form-group">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="title" class="form-label">{{ __('Product Title') }}<span class="text-danger">*</span> <span class="text-success">(Product Name | Author | Edition | Publication ( Medium ) )</span></label>
                                    <span id="charCount">0/145</span>
                                </div>

                                <input maxlength="145" id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? $listing->title }}" autocomplete="title" autofocus placeholder="title">
                                <span class="error-message title" style="color:red;"></span>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Product Description') }}<span class="text-danger">*</span><span class="text-success"> (Suggestion - Title + Description + Search Key) </span></div>
                                    <div>
                                        <a href='https://www.commontools.org/tool/replace-new-lines-with-commas-40' target='_blank'>Line Remover | </a><a target='_blank' href="https://chat.openai.com"> ChatGPT</a>
                                    </div>
                                </label>
                                <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                    <div>{{ __('Do not use 3rd Party Links/Website Names') }}</div>
                                </label>

                                <!-- <label for="description" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Product Description') }}<span class="text-danger">*</span><span class="text-danger"> ( Enter Detail Description without using 3rd party
                                            link) </span></div><a target='_blank' href="https://chat.openai.com">ChatGPT</a>
                                </label> -->
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Description" rows="10" id='desc'>{!! old('description') ?? $listing->description !!}</textarea>
                                <span class="error-message desc" style="color:red;"></span>

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
                                <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail
                                        Price)</span></label>
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') ?? $listing->mrp }}" autocomplete="mrp" autofocus placeholder="MRP">

                                @error('mrp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="selling_price" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Selling Price') }}<span class="text-danger">*</span></div>
                                    <div>
                                        <a href='https://support.exam360.co.in/' target='_blank'>Calculator |</a><a target='_blank' href="https://docs.google.com/spreadsheets/d/1uSqo6RhsLHaVcVrkEjO_SmOWiXqWBC-aV1LvsowgsL0/"> Disc. Info.</a>

                                    </div>
                                </label>
                                <input id="selling_price" type="number" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') ?? $listing->selling_price }}" autocomplete="selling_price" autofocus placeholder="Selling Price">

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
                                <input id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') ?? $listing->publisher }}" autocomplete="publication" autofocus placeholder="Publication">
                                <span class="error-message publication" style="color:red;"></span>

                                @error('publication')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="author_name" class="form-label">{{ __('Author Name') }}<span class="text-danger">*</span></label>
                                    <span class="charCount">0/35</span>
                                </div>

                                <input maxlength="35" id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') ?? $listing->author_name }}" autocomplete="author_name" autofocus placeholder="Author name">
                                <span class="error-message author_name" style="color:red;"></span>

                                @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                <input id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') ?? $listing->edition }}" autocomplete="edition" autofocus placeholder="Edition">
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
                            <label for="label" class="form-label">{{ __('Category') }}<span class="text-danger">*</span><span class="text-danger"> ( Publication, 1 Category,
                                    1 Tag, Others ) </span></label>
                            <select class="form-control select2  @error('label') is-invalid @enderror" data-placeholder="Choose Label" multiple name="label[]">
                                @foreach ($listing->categories as $category)
                                <option value="{{ $category }}" selected>
                                    {{ $category }}
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
                                <label for="sku" class="form-label">{{ __('SKU') }}<span class="text-danger">*</span><span class="text-danger"> ( Short Code )
                                    </span></label>
                                <input id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') ?? $listing->sku }}" autocomplete="sku" autofocus placeholder="SKU">
                                <span class="error-message sku" style="color:red;"></span>

                                @error('sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="language" class="form-label">{{ __('Language') }}<span class="text-danger">*</span></label>
                                <input id="language" type="text" class="form-control @error('language') is-invalid @enderror" name="language" value="{{ old('language') ?? $listing->language }}" autocomplete="language" autofocus placeholder="Language">
                                <span class="error-message language" style="color:red;"></span>

                                @error('language')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                                <input id="pages" type="text" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') ?? $listing->no_of_pages }}" autocomplete="pages" autofocus placeholder="No. of Pages">
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
                                    <option value="New" {{ $listing->condition == 'New' ? 'selected' : '' }}>New
                                    </option>
                                    <option value="Like New" {{ $listing->condition == 'Like New' ? 'selected' : '' }}>Like New</option>
                                    <option value="Old" {{ $listing->condition == 'Old' ? 'selected' : '' }}>Old
                                    </option>
                                </select>

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
                                    <option value="Hardcover" {{ $listing->binding_type == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                                    <option value="Paperback" {{ $listing->binding_type == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                                </select>
                                <span class="error-message binding" style="color:red;"></span>

                                @error('binding')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Insta Mojo URL') }}</label>
                                <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') ?? $listing->insta_mojo_url }}" autocomplete="url" autofocus placeholder="Insta Mojo Url">
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
                                <input id="url" type="text" value="{{ $listing->images }}" class="form-control @error('images') is-invalid @enderror" name="images[]" autocomplete="images" autofocus placeholder="Base URL">
                                <span class="error-message images" style="color:red;"></span>

                                @error('base_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            @if($listing->multiple_images)
                            @foreach ($listing->multiple_images as $key => $images)
                            <div class="form-group col-md-4">
                                <label for="url" class="form-label">{{ __('Additional Image URL') }}</label>
                                <div class="input-group align-items-center">
                                    <input type="text" class="form-control @error('multipleImages') is-invalid @enderror" name="multipleImages[]" value="{{ old('multipleImages') ?? $images }}" autocomplete="multipleImages" autofocus placeholder="Additional Image URL">
                                    <div class="input-group-append">
                                        <img src="/assets/images/cross.png" class="removeFileInput" style="width:25px;margin-left:5px;" />
                                    </div>
                                </div>
                                <span class="error-message multipleImages" style="color:red;"></span>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <button type='button' id='addFileInput' class="btn btn-primary">Add More
                                    Images</button>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <button class="btn btn-danger float-right" id='reject'>Reject ( DB )</button>
                            <button class="btn btn-warning float-right" id='update'>Update ( DB )</button>
                            <button class="btn btn-dark float-right" id='draft'>Save as Draft</button>
                            <button class="btn btn-success float-right" id='publish'>Publish to Website</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div id="fileInputContainer">
                            <div class="form-group">
                                <label for="fileInput1">Main Images<span class="text-danger">*</span></label>
                                <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="fileInput1" name="images[]" value={{ $listing->images }} data-default-file={{ $listing->images }} />
                                <a href="{{ route('process.image') }}?url={{ $listing->images }}" download="image.jpg" class="w-100 d-flex justify-content-end my-4"><img src="/downlod-icon.png" /></a>

                                @error('images')
                                <span class="invalid-feedback mt-2" style="display:block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <div id='fileInputContainer'>
                                    @if ($listing->multiple_images && count($listing->multiple_images) != 1)
                                    <label for="fileInput1" class="mt-2">Preview Images<span class="text-danger">*</span></label><br>
                                    @foreach ($listing->multiple_images as $key => $images)
                                    @if ($key == 0)
                                    @continue;
                                    @endif
                                    <div class="input-group{{ $key }}">
                                        <input type="hidden" name="multipleImages[]" value={{ $images }} />
                                        <div class="form-group mb-1" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                            <input data-default-file={{ $images }} id="demo" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-danger removeFileInput mb-3" id='{{ $key }}'>Remove</button>
                                            <a href='{{ route('process.image') }}?url={{ $images }}' download="image.jpg"><img src="/downlod-icon.png" /></a>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>

                                <label for="fileInput1" class="mb-0">Images<span class="text-danger">*</span>(
                                    Multiple Images )</label>
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
        </div> -->
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        })

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        });

        $("#draft").click(function(e) {
            e.preventDefault();
            $("#formTest").append("<input type='hidden' name='isDraft' value=1 />");
            $("#formTest").attr("action", "{{ route('listing.store') }}");
            $('[name="_method"]').remove();

            $("#formTest").submit();
        });

        $("#publish").click(function(e) {
            e.preventDefault();
            $("#formTest").attr("action", "{{ route('listing.store') }}");
            $("#formTest").append("<input type='hidden' name='database' value={{$listing->id}} />");
            $("#formTest").append("<input type='hidden' name='status' value=1 />");
            $('[name="_method"]').remove();
            $("#formTest").submit();
        });

        $("#update").click(function(e) {
            e.preventDefault();
            $("#formTest").attr("action", "{{ route('database-listing.update', $listing->id) }}");
            $("#formTest").submit();
        });

        $("#reject").click(function(e) {
            e.preventDefault();
            $("#formTest").attr("action", "{{ route('database-listing.update', $listing->id) }}");
            $("#formTest").append("<input type='hidden' name='status' value=2 />");
            $("#formTest").submit();
        });
    });
</script>

@include('listing.script')
@endpush