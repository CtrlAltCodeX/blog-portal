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
                @if(request()->edit)
                @method('PUT')
                @else
                @method('POST')
                @endif
                <input type="hidden" name="created_by" value="{{ $listing->created_by }}" />
                <input type="hidden" name="product_url" value="{{ $listing->url }}" />
                @if(request()->edit)
                <input type="hidden" name="edit" value="true" />
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update New Listing ( DB )') }}
                        </h4>
                        @can('Create Duplicate Listing Button')
                        <button type='submit' class="btn btn-info duplicate_listing">Create Duplicate Listing</button>
                        @endif
                        <!-- <button type="submit" class="btn btn-primary float-right">Save</button> -->
                    </div>

                    <div class="card-body">
                        <div id="progressBar" class="text-end"></div>

                        <div>
                            <div class="form-group">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="title" class="form-label">{{ __('Product Title') }}<span class="text-danger">*</span> <span class="text-success">(Product Name | Author | Edition | Publication ( Medium ) )</span></label>
                                    <span id="charCount">0/160</span>
                                </div>
                                <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                    <div>{{ __('Excess Capitalism in Product Title Not Allowed') }}</div>
                                </label>

                                <input minlength="75" maxlength="160" id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? $listing->title }}" autocomplete="title" autofocus placeholder="title">
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
                                    <div class="d-flex">
                                        <a href='{{ $siteSetting->listing_button_1_link }}' target='_blank'>{{ $siteSetting->listing_button_1 }} | &nbsp;</a><a target='_blank' href="{{ $siteSetting->listing_button_2_link }}"> {{ $siteSetting->listing_button_2 }} | </a>
                                        <a href='https://www.commontools.org/tool/replace-new-lines-with-commas-40' target='_blank'>&nbsp;Line Remover</a>
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
                            <div class="form-group col-md-4">
                                <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail
                                        Price)</span></label>
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') ?? $listing->mrp }}" autocomplete="mrp" autofocus placeholder="MRP">

                                @error('mrp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="discount" class="form-label">{{ __('Discount ( % )') }}</label>
                                <input id="discount" name="discount" type="number" class="form-control" placeholder="Discount ( % )">
                                <span class="error-message discount" style="color:red;"></span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="selling_price" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Selling Price') }}<span class="text-danger">*</span></div>
                                    <div>
                                        <a href='{{ $siteSetting->calc_link }}' target='_blank'>Calculator</a>
                                        <!--<a target='_blank' href="https://docs.google.com/spreadsheets/d/1uSqo6RhsLHaVcVrkEjO_SmOWiXqWBC-aV1LvsowgsL0/"> Disc. Info.</a>-->

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
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="publication" class="form-label">{{ __('Publisher') }}<span class="text-danger">*</span></label>
                                    <span class="charCount">0/35</span>
                                </div>

                                <input maxlength="35" id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') ?? $listing->publisher }}" autocomplete="publication" autofocus placeholder="Publication">
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
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="edition" class="form-label">{{ __('Edition') }}</label>
                                    <span class="charCount">0/20</span>
                                </div>

                                <input maxlength="20" id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') ?? $listing->edition }}" autocomplete="edition" autofocus placeholder="Edition">
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
                            <div class="d-flex justify-content-between align-between-center">
                                <label for="label" class="form-label">{{ __('Category') }}<span class="text-danger">*</span><span class="text-danger"> ( Publication, 1 Category, 1 Tag, Others ) </span></label>

                                <div class="d-flex flex-column">
                                    <div id='count'><strong>Label Selected</strong> : 1</div>
                                    <div id='textLength'></div>
                                </div>
                            </div>
                            <select class="form-control select2  @error('label') is-invalid @enderror" data-placeholder="Choose Label" multiple name="label[]">
                                @foreach ($categories as $categoryData)
                                <option value="{{ $categoryData['term'] }}" @foreach($listing->categories as $label) @if($categoryData['term']==$label) selected @endif @endforeach>
                                    {{ $categoryData['term'] }}
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
                            <div class="col-md-9">
                                <div class="row" id="addUrls">
                                    <div class="col-md-4">
                                        <div class="">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label for="sku" class="form-label">
                                                    {{ __('ISBN 10') }}
                                                </label>
                                            </div>
                                            <input type="text" class="form-control " name="isbn_10" value="{{ $listing->isbn_10 }}" autofocus placeholder="ISBN 10">
                                            <span class="error-message isbn_10" style="color:red;"></span>

                                            @error('isbn_10')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label for="sku" class="form-label">
                                                    {{ __('ISBN 13') }}
                                                </label>
                                            </div>
                                            <input id="isbn_13" type="text" class="form-control @error('isbn_13') is-invalid @enderror" name="isbn_13" value="{{ $listing->isbn_13 }}" autofocus placeholder="ISBN 13">
                                            <span class="error-message isbn_13" style="color:red;"></span>

                                            @error('isbn_13')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label for="publish_year" class="form-label">
                                                    {{ __('Publish Year') }}
                                                </label>
                                            </div>
                                            <input type="month" class="form-control @error('publish_year') is-invalid @enderror" name="publish_year" value="{{ $listing->publish_year }}">
                                            <span class="error-message publish_year" style="color:red;"></span>

                                            @error('publish_year')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label for="sku" class="form-label">
                                                {{ __('SKU') }}
                                                <span class="text-danger">*</span>
                                                <span class="text-danger"> ( Short Code )</span>
                                            </label>
                                            <span class="charCount">0/30</span>
                                        </div>
                                        <input maxlength="30" id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') ?? $listing->sku }}" autocomplete="sku" autofocus placeholder="SKU">
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
                                        <label for="condition" class="form-label">{{ __('Product Condition') }}<span class="text-danger">*</span></label>

                                        <select class="form-control @error('condition') is-invalid @enderror" name="condition" value="{{ old('condition') }}">
                                            <option value="">--Select--</option>
                                            <option value="New" {{ $listing->condition == 'New' ? 'selected' : '' }}>New</option>
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

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="weight" class="form-label d-flex justify-content-between">{{ __('Weight (grams)') }}</label>
                                            <input type="number" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ $listing->weight }}" autocomplete="weight" autofocus placeholder="Weight (g)">
                                            <span class="error-message weight" style="color:red;"></span>

                                            @error('weight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="reading_age" class="form-label d-flex justify-content-between">{{ __('Reading Age') }}</label>
                                            <input style="background-color: #e9f85c;" id="reading_age" type="text" class="form-control @error('reading_age') is-invalid @enderror" name="reading_age" value="{{ $listing->reading_age??'Above 10 Years' }}" autocomplete="reading_age" autofocus placeholder="Reading Age">
                                            <span class="error-message reading_age" style="color:red;"></span>

                                            @error('reading_age')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="country_origin" class="form-label d-flex justify-content-between">{{ __('Country of Origin') }}</label>
                                            <input style="background-color: #e9f85c;" id="country_origin" type="country_origin" class="form-control @error('country_origin') is-invalid @enderror" name="country_origin" value="{{ $listing->country_origin??'India' }}" autocomplete="country_origin" autofocus placeholder="Country of Origin">
                                            <span class="error-message country_origin" style="color:red;"></span>

                                            @error('country_origin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="genre" class="form-label d-flex justify-content-between">{{ __('Genre') }}</label>
                                            <input id="genre" type="genre" class="form-control @error('genre') is-invalid @enderror" name="genre" value="{{ $listing->genre??'Books' }}" autocomplete="genre" autofocus placeholder="Genre">
                                            <span class="error-message genre" style="color:red;"></span>

                                            @error('genre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="manufacturer" class="form-label d-flex justify-content-between">{{ __('Manufacturer') }}</label>
                                            <input style="background-color: #e9f85c;" id="manufacturer" type="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" name="manufacturer" value="{{ $listing->manufacturer??'As Per Publisher' }}" autocomplete="manufacturer" autofocus placeholder="Manufacturer">
                                            <span class="error-message manufacturer" style="color:red;"></span>

                                            @error('manufacturer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="importer" class="form-label d-flex justify-content-between">{{ __('Importer') }}</label>
                                            <input style="background-color: #e9f85c;" id="importer" type="importer" class="form-control @error('importer') is-invalid @enderror" name="importer" value="{{ $listing->importer??'Not Applicable' }}" autocomplete="importer" autofocus placeholder="Importer">
                                            <span class="error-message importer" style="color:red;"></span>

                                            @error('importer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="packer" class="form-label d-flex justify-content-between">{{ __('Packer') }}</label>
                                            <input style="background-color: #e9f85c;" id="packer" type="packer" class="form-control @error('packer') is-invalid @enderror" name="packer" value="{{ $listing->packer??'Fullfilled by Supplier' }}" autocomplete="packer" autofocus placeholder="Packer">
                                            <span class="error-message packer" style="color:red;"></span>

                                            @error('packer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="url" class="form-label d-flex justify-content-between">{{ __('Insta Mojo URL') }}<span onclick="copyLink()" id='copylink' style="cursor:pointer;">Copy</span></label>
                                        <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') ?? $listing->insta_mojo_url }}" autocomplete="url" autofocus placeholder="Insta Mojo Url">
                                        <span class="error-message url" style="color:red;"></span>

                                        @error('url')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="url" class="form-label">{{ __('Main Image URL') }}</label>
                                        <input id="base_url" type="text" value="{{ (isset($listing->images[0]) && $listing->images[0]!='h') ? $listing->images[0] : $listing->images }}" class="form-control @error('images') is-invalid @enderror" name="images[]" autocomplete="images" autofocus placeholder="Base URL">
                                        <span class="error-message images" style="color:red;"></span>

                                        @error('base_url')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-right mb-2 d-flex" id='preview' style="justify-content: flex-end;">
                                <div style="border: 2px solid #ccc;width: 300px;max-height: 300px;height:300px;">
                                    <img src="{{ (isset($listing->images[0]) && $listing->images[0]!='h') ? $listing->images[0] : $listing->images }}" id='previewImage' />
                                    <div class='image-status' style="text-align: center;padding: 5px;display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if($listing->multiple_images)
                            @php
                            $allImages = [];
                            if((request()->edit)) {
                            $allImages = json_decode($listing->multiple_images??[]);
                            } else {
                            $allImages = $listing->multiple_images??[];
                            }
                            @endphp
                            @foreach ($allImages as $key => $images)
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
                            @if(request()->edit)
                            @can('Pending Listing ( DB ) -> Publish to Website')
                            <button class="btn btn-success float-right" id='website'>Update to Website</button>
                            @endcan
                            @else
                            <button class="btn btn-warning float-right" id='update'>Request for update</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
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
<script>
    $(document).ready(function() {
        $("#update").click(function(e) {
            e.preventDefault();
            $("#formTest").attr("action", "{{ route('listing.publish.database') }}");
            $("#formTest").append("<input type='hidden' name='database' value={{$listing->product_id}} />");
            $("#formTest").submit();
        });

        $("#website").click(function(e) {
            e.preventDefault();
            $("#formTest").attr("action", "{{ route('listing.update', $listing->product_id) }}");
            $("#formTest").append("<input type='hidden' name='product_id' value={{$listing->product_id}} />");
            $("#formTest").submit();
        });

        $(".duplicate_listing").click(function(e) {
            e.preventDefault();
            $("#formTest").attr("action", "{{ route('listing.publish.database') }}");
            $("#formTest").append("<input type='hidden' name='duplicate' value=1 />");
            $("input[name=_method]").val("POST");
            $("#formTest").submit();
        });
    });
</script>

@include('listing.script')
@endpush