@extends('layouts.master')

@section('title', __('Create New Listing'))

@push('css')
<style>
    hr {
        border: 1px solid #ccc;
        width: 100%;
        height: 0px !important;
        margin-top: 0px;
    }

    .alert-msg {
        background-color: grey;
        color: white;
    }
</style>
@endpush

@section('content')
<form action="{{ route('database_temp') }}" method="POST" enctype='multipart/form-data' id='form'>
    @csrf
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">{{ __('Create New Listing (DB)') }}</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Listing') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Create New Listing') }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9 col-xl-12 fields">

                <div class="card">
                    <div class="card-body">
                        <span class="d-flex justify-content-center mb-4 alert-msg">Alert: Please refrain from creating duplicate listings repeatedly. Prior to creating any new listings, ensure to first check the product in 'Search Listing (M/S)'.</span>
                        <hr />

                        <div id="progressBar" class="text-end"></div>
                        <div>
                            <div class="form-group">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="title" class="form-label">{{ __('Product Title') }}<span class="text-danger">*</span> <span class="text-success">(Product Name | Author | Edition | Publication ( Medium ) )</span></label>
                                    <span class="charCount">0/160</span>
                                </div>
                                <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                    <div>{{ __('Excess Capitalism in Product Title Not Allowed') }}</div>
                                </label>

                                <input minlength="75" maxlength="160" id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Title">
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Product Description') }}<span class="text-danger">*</span><span class="text-success"> (Suggestion - Title + Description + Search Key) </span></div>
                                    <div class="d-flex">
                                        <a href='{{ $siteSetting->listing_button_1_link }}' target='_blank'>{{ $siteSetting->listing_button_1 }} | &nbsp;</a><a target='_blank' href="{{ $siteSetting->listing_button_2_link }}"> {{ $siteSetting->listing_button_2 }} | </a>
                                        <a href='https://www.commontools.org/tool/replace-new-lines-with-commas-40' target='_blank'>&nbsp;Line Remover</a>
                                    </div>
                                </label>
                                <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                    <div>{{ __('Do not use 3rd Party Links/Website Names') }}</div>
                                </label>

                                <textarea type="text" class="form-control" name="description" placeholder="Description" rows="10">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail
                                        Price)</span></label>
                                <input id="mrp" type="number" class="form-control" name="mrp" value="{{ old('mrp') }}" autocomplete="mrp" autofocus placeholder="MRP">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="discount" class="form-label">{{ __('Discount ( % )') }}</label>
                                <input id="discount" name="discount" type="number" class="form-control" placeholder="Discount ( % )">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="selling_price" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Selling Price') }}<span class="text-danger">*</span></div>
                                    <div>
                                        <a href='{{ $siteSetting->calc_link }}' target='_blank'>Calculator |</a><a target='_blank' href="https://docs.google.com/spreadsheets/d/1uSqo6RhsLHaVcVrkEjO_SmOWiXqWBC-aV1LvsowgsL0/"> Disc. Info.</a>
                                    </div>
                                </label>
                                <input id="selling_price" type="number" class="form-control" name="selling_price" value="{{ old('selling_price') }}" autocomplete="selling_price" autofocus placeholder="Selling Price">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="publication" class="form-label">{{ __('Publisher') }}<span class="text-danger">*</span></label>
                                    <span class="charCount">0/35</span>
                                </div>

                                <input maxlength="35" id="publication" type="text" class="form-control" name="publication" value="{{ old('publication') }}" autocomplete="publication" autofocus placeholder="Publisher">
                                <span class="error-message publication" style="color:red;"></span>


                            </div>

                            <div class="form-group col-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="author_name" class="form-label">{{ __('Author Name') }}<span class="text-danger">*</span></label>
                                    <span class="charCount">0/35</span>
                                </div>

                                <input maxlength="35" id="author_name" type="text" class="form-control" name="author_name" value="{{ old('author_name') }}" autocomplete="author_name" autofocus placeholder="Author name">
                            </div>

                            <div class="form-group col-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                    <span class="charCount">0/20</span>
                                </div>

                                <input maxlength="20" id="edition" type="text" class="form-control" name="edition" value="{{ old('edition') }}" autocomplete="edition" autofocus placeholder="Edition">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-between-center">
                                <label for="label" class="form-label">{{ __('Category') }}<span class="text-danger">*</span><span class="text-danger"> ( Publication, 1 Category, 1 Tag, Others ) </span></label>

                                <div class="d-flex flex-column">
                                    <div id='count'><strong>Label Selected</strong> : 1</div>
                                    <div id='textLength'></div>
                                </div>
                            </div>
                            <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                <div>{{ __('Note - You need to Select Min. 8 Perfect Categories to go Your Listing Live. Else It may be Rejected.') }}</div>
                            </label>
                            <select class="form-control select2 " data-placeholder="Choose Label" multiple name="label[]">
                                @foreach ($categories as $category)
                                <option value="{{ $category['term'] }}" {{ $category['term'] == 'Product' ? 'selected' : '' }}>
                                    {{ $category['term'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-9" id="addUrls">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label for="sku" class="form-label">
                                                {{ __('SKU') }}
                                                <span class="text-danger">*</span>
                                                <span class="text-danger"> ( Short Code )</span>
                                            </label>
                                            <span class="charCount">0/30</span>
                                        </div>
                                        <input maxlength="30" id="sku" type="text" class="form-control" name="sku" value="{{ old('sku') }}" autocomplete="sku" autofocus placeholder="SKU">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="language" class="form-label">{{ __('Language') }}<span class="text-danger">*</span></label>
                                        <input id="language" type="text" class="form-control" name="language" value="{{ old('language') }}" autocomplete="language" autofocus placeholder="Language">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                                        <input id="pages" type="text" class="form-control" name="pages" value="{{ old('pages') }}" autocomplete="pages" autofocus placeholder="No. of Pages">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="condition" class="form-label">{{ __('Condition') }}<span class="text-danger">*</span></label>
                                        <select class="form-control" name="condition" value="{{ old('condition') }}">
                                            <option value="">--Select--</option>
                                            <option {{ old('condition') == 'New' ? 'selected' : '' }} value="New">New</option>
                                            <option {{ old('condition') == 'Like New' ? 'selected' : '' }} value="Like New">Like New</option>
                                            <option {{ old('condition') == 'Old' ? 'selected' : '' }} value="Old">Old</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="binding" class="form-label">{{ __('Binding Type') }}<span class="text-danger">*</span></label>
                                        <select class="form-control" name="binding" value="{{ old('binding') }}">
                                            <option value="">--Select--</option>
                                            <option {{ old('binding') == 'Hardcover' ? 'selected' : '' }} value="Hardcover">Hardcover</option>
                                            <option {{ old('binding') == 'Paperback' ? 'selected' : '' }} value="Paperback">Paperback</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="url" class="form-label d-flex justify-content-between">{{ __('Insta Mojo URL') }}<span onclick="copyLink()" id='copylink' style="cursor:pointer;">Copy</span></label>
                                        <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" autocomplete="url" autofocus placeholder="Insta Mojo url">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="url" class="form-label">{{ __('Main Image URL') }}</label>
                                        <input id="base_url" type="text" value="{{ old('images')[0]??'' }}" class="form-control @error('images') is-invalid @enderror" name="images[]" autocomplete="images" autofocus placeholder="Base URL">

                                        <button type='button' id='addFileInput' class="btn btn-primary m-2">Add More Images</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-right mb-2 d-flex" id='preview' style="justify-content: flex-end;">
                                <div style="border: 2px solid #ccc;width: 300px;max-height: 300px;height:300px;">
                                    <img src="" id='previewImage' />
                                    <div class='image-status' style="text-align: center;padding: 5px;display:none;"></div>
                                </div>
                            </div>
                        </div>

                        @if(!$user_data_transfer)
                        <div style="text-align: right;margin-top:30px;">
                            <button class="btn btn-success float-right"><i class="fa fa-eye"></i> Preview</button>
                        </div>
                        @else
                        <div style="text-align: right;margin-top:30px;">
                            <button type="submit" class="btn btn-success float-right">Confirm & Save ( DB )</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <!-- End Row -->
    </div>
</form>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- INTERNAL File-Uploads Js-->
<script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>


<script>
    function copyLink() {
        // Get the text field
        var copyText = document.getElementById("url");

        $("#copylink").html('Copied');

        navigator.clipboard.writeText('https://www.instamojo.com/EXAM360/');

        setTimeout(function() {
            $('#copylink').html('Copy');
        }, 500)
    }

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
        });

        $('#base_url').on('change', function() {
            const imageUrl = $(this).val();
            const $image = $('#previewImage');
            const $status = $('.image-status');
            // Clear previous content
            $status.empty();
            $image.hide();

            // Validate URL and load image
            if (imageUrl) {
                $image.attr('src', imageUrl).on('load', function() {
                    const width = this.naturalWidth;
                    const height = this.naturalHeight;

                    // Show the image
                    $image.show();

                    // Check dimensions
                    if (width === 555 && height === 555 || width === 320 && height === 320) {
                        $('.image-status').show();
                        $('.image-status').css('background-color', 'green');
                        $status.html('<span style="color: white;">✔ Image Size is Perfect</span>');
                    } else {
                        $('.image-status').show();
                        $('.image-status').css('background-color', 'red');
                        $status.html('<span style="color: white;">✖ Image Pixels Issues Found</span>');
                    }
                }).on('error', function() {
                    $('.image-status').show();
                    $('.image-status').css('background-color', 'red');
                    $status.html('<span style="color: white;">✖ Failed to load image. Check the URL.</span>');
                });
            } else {
                $('.image-status').show();
                $('.image-status').css('background-color', 'red');
                $status.html('<span style="color: white;">✖ Please enter a valid image URL.</span>');
            }
        });

        $('input').on('input', function() {
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');
            
            if (inputName == 'discount' ||
                inputName == 'mrp'
            ) {
                var discount = parseInt($('#discount').val());
                var mrp = parseInt($("#mrp").val());
    
                if (discount <= 100) {
                    var discountedPrice = (mrp * discount) / 100;
    
                    $('#selling_price').val(Math.round(mrp - discountedPrice));
                } else {
                    $('#discount').val(0);
                }
            }
    
            if (inputName == 'selling_price') {
                var sellingPrice = $(this).val();
                var mrp = parseInt($("#mrp").val());
    
                $('#discount').val(Math.round(((mrp - sellingPrice) / mrp) * 100));
            }
        });

    })
</script>
@endpush