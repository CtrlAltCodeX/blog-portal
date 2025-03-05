@extends('layouts.master')

@section('title', __('Create Listing ( M/S )'))

@push('css')
<style>
    hr {
        border: 1px solid #ccc;
        width: 100%;
        height: 0px !important;
        margin-top: 0px;
    }

    .alert-msg {
        background-color: #808007;
        color: white;
    }
</style>
@endpush

@section('content')

<!-- CONTAINER -->
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Listing ( M/S )') }}</h1>
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
        <div class="col-md-9 col-xl-12 fields">
            <form action="{{ route('listing.store') }}" method="POST" enctype='multipart/form-data' id='form'>
                @csrf
                <div class="card">
                    <!-- <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create Listing') }}
                        </h4>
                    </div> -->

                    <div class="card-body">
                        <span class="d-flex justify-content-center mb-4 alert-msg text-center align-items-center" style='grid-gap:5px;'><i class='fa fa-warning'></i><strong>Alert:</strong> Please refrain from creating duplicate listings repeatedly. Prior to creating any new listings, ensure to first check the product in 'Search Listing (M/S)'.</span>
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

                                <input minlength='75' maxlength="160" id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title" autofocus placeholder="Title">
                                <span class="error-message title" style="color:red;"></span>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label d-flex justify-content-between">
                                    <div>{{ __('Description') }}<span class="text-danger">*</span><span class="text-success"> (Suggestion - Title + Description + Search Key) </span></div>
                                    <div class="d-flex">
                                        <a href='{{ $siteSetting->listing_button_1_link }}' target='_blank'>{{ $siteSetting->listing_button_1 }} | &nbsp;</a><a target='_blank' href="{{ $siteSetting->listing_button_2_link }}"> {{ $siteSetting->listing_button_2 }} | </a>
                                        <a href='https://www.commontools.org/tool/replace-new-lines-with-commas-40' target='_blank'>&nbsp;Line Remover</a>
                                    </div>
                                </label>
                                <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                    <div>{{ __('Do not use 3rd Party Links/Website Names') }}</div>
                                </label>
                                <!-- <div id="summernote" id="description" class="form-control @error('description') is-invalid @enderror" name="description">
                                    {{ old('description') }}
                                </div> -->

                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description" autofocus placeholder="Description" rows="10" id='desc'>{{ old('description') }}</textarea>
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
                                <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail Price)</span></label>
                                <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') }}" autocomplete="mrp" autofocus placeholder="MRP">
                                <span class="error-message mrp" style="color:red;"></span>

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


                

                <!-- weight and couriers -->

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                            <label for="pub_name" class="form-label">{{ __('Select Publication') }}</label>
                            <select class="genre form-control" name="pub_name" id="pub_name">
    <option value="">--Select Publication--</option>
    @foreach($publications as $pub)
        <option value="{{ $pub->id }}">{{ $pub->pub_name }}</option>
    @endforeach
</select>


                            </div>

                            <div class="form-group col-md-4">
                <label for="book_name" class="form-label">{{ __('Select Book Type') }}</label>
                <select class="form-control" name="book_name" id="book_name">
                    <option value="">-- Select Book --</option>
                </select>
            </div>


            <div class="form-group col-md-4">
    <label class="form-label">{{ __('Selling Prices') }}</label>
    <div class="selling-prices">
        <strong>Min Profit:</strong> <span id="selling_price1">--</span> <br>
        <strong>Max Profit:</strong> <span id="selling_price2">--</span>
    </div>
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

                                <input maxlength="35" id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') }}" autocomplete="publication" autofocus placeholder="Publisher">
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

                                <input maxlength="35" id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') }}" autocomplete="author_name" autofocus placeholder="Author name">
                                <span class="error-message author_name" style="color:red;"></span>

                                @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="edition" class="form-label">{{ __('Edition') }} <span class="text-danger">*</span></label>
                                    <span class="charCount">0/20</span>
                                </div>

                                <input maxlength="20" id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') }}" autocomplete="edition" autofocus placeholder="Edition">
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
                            <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                                <div>{{ __('Note - You need to Select Min. 8 Perfect Categories to go Your Listing Live. Else It may be Rejected.') }}</div>
                            </label>
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
                            <div class="col-md-9">
                                <div class="row" id="addUrls">
                                    <div class="col-md-4">
                                        <div class="">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label for="isbn_10" class="form-label">
                                                    {{ __('ISBN 10') }} <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control" name="isbn_10" value="{{ old('isbn_10') }}" autofocus placeholder="ISBN 10">
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
                                                <label for="isbn_13" class="form-label">
                                                    {{ __('ISBN 13') }} <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <input id="isbn_13" type="text" class="form-control @error('isbn_13') is-invalid @enderror" name="isbn_13" value="{{ old('isbn_13') }}" autofocus placeholder="ISBN 13">
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
                                                    {{ __('Publish Year') }}<span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <input type="month" class="form-control @error('publish_year') is-invalid @enderror" name="publish_year">
                                            <span class="error-message publish_year" style="color:red;"></span>

                                            @error('publish_year')
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
                                                    {{ __('SKU') }}
                                                    <span class="text-danger">*</span>
                                                    <span class="text-danger"> ( Short Code )</span>
                                                </label>
                                                <span class="charCount">0/30</span>
                                            </div>
                                            <input maxlength="30" id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') }}" autocomplete="sku" autofocus placeholder="SKU">
                                            <span class="error-message sku" style="color:red;"></span>

                                            @error('sku')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="language" class="form-label">{{ __('Language') }}<span class="text-danger">*</span></label>
                                            <input id="language" type="text" class="form-control @error('language') is-invalid @enderror" name="language" value="{{ old('language') }}" autocomplete="language" autofocus placeholder="Language">
                                            <span class="error-message language" style="color:red;"></span>

                                            @error('language')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="pages" class="form-label">{{ __('No. of Pages') }}<span class="text-danger">*</span></label>
                                            <input id="pages" type="text" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') }}" autocomplete="pages" autofocus placeholder="No. of Pages">
                                            <span class="error-message pages" style="color:red;"></span>

                                            @error('pages')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="condition" class="form-label">{{ __('Product Condition') }}<span class="text-danger">*</span></label>
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
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
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
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="weight" class="form-label d-flex ">{{ __('Weight (grams)') }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ old('weight') }}" autocomplete="weight" autofocus placeholder="Weight (g)">
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
                                            <label for="reading_age" class="form-label d-flex ">{{ __('Reading Age') }} <span class="text-danger">*</span></label>
                                            <select class="form-control @error('reading_age') is-invalid @enderror" name="reading_age" value="{{ old('reading_age') }}">
                                                <option value="">--Select--</option>
                                                <option value="Above 18 Years">Above 18 Years</option>
                                                <option value="Above 10 Years">Above 10 Years</option>
                                                <option value="Above 5 Years">Above 5 Years</option>
                                                <option value="Above 3 Years">Above 3 Years</option>
                                            </select>
                                            <!-- <input style="background-color: #e9f85c;" id="reading_age" type="text" class="form-control @error('reading_age') is-invalid @enderror" name="reading_age" value="{{ old('reading_age')??'Above 10 Years' }}" autocomplete="reading_age" autofocus placeholder="Reading Age"> -->
                                            <!-- <span class="error-message reading_age" style="color:red;"></span> -->

                                            @error('reading_age')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="country_origin" class="form-label d-flex ">{{ __('Country of Origin') }} <span class="text-danger">*</span></label>
                                            <input style="background-color: #e9f85c;" id="country_origin" type="country_origin" class="form-control @error('country_origin') is-invalid @enderror" name="country_origin" value="India" autocomplete="country_origin" autofocus placeholder="Country of Origin" readonly>
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
                                            <label for="genre" class="form-label d-flex ">{{ __('Genre') }} <span class="text-danger">*</span></label>
                                            <select  class=" genre form-control @error('genre') is-invalid @enderror" name="genre" value="{{ old('genre') }}">
    <option value="">--Select--</option>
    <option value="Fantasy">Fantasy</option>
    <option value="Horror">Horror</option>
    <option value="Romance">Romance</option>
    <option value="Science fiction">Science fiction</option>
    <option value="Adventure fiction">Adventure fiction</option>
    <option value="Fiction">Fiction</option>
    <option value="Mystery">Mystery</option>
    <option value="Fairy tale">Fairy tale</option>
    <option value="Thriller">Thriller</option>
    <option value="Young adult">Young adult</option>
    <option value="Historical">Historical</option>
    <option value="Literary fiction">Literary fiction</option>
    <option value="Comedy">Comedy</option>
    <option value="Crime">Crime</option>
    <option value="Short story">Short story</option>
    <option value="Classics">Classics</option>
    <option value="Drama">Drama</option>
    <option value="Dystopian Fiction">Dystopian Fiction</option>
    <option value="Gothic fiction">Gothic fiction</option>
    <option value="Graphic novel">Graphic novel</option>
    <option value="Magic realism">Magic realism</option>
    <option value="Mystery and suspense">Mystery and suspense</option>
    <option value="Paranormal romance">Paranormal romance</option>
    <option value="School Books">School Books</option>
    <option value="NCERT Books">NCERT Books</option>
    <option value="Competitive Books">Competitive Books</option>
    <option value="Medical Books">Medical Books</option>
    <option value="Dental Books">Dental Books</option>
    <option value="Action & Adventure">Action & Adventure</option>
    <option value="Arts, Film & Photography">Arts, Film & Photography</option>
    <option value="Biographies, Diaries & True Accounts">Biographies, Diaries & True Accounts</option>
    <option value="Business & Economics">Business & Economics</option>
    <option value="Children's Books">Children's Books</option>
    <option value="Comics & Graphic Novels">Comics & Graphic Novels</option>
    <option value="Computers & Internet">Computers & Internet</option>
    <option value="Crafts, Hobbies & Home">Crafts, Hobbies & Home</option>
    <option value="Crime, Thriller & Mystery">Crime, Thriller & Mystery</option>
    <option value="Engineering Books">Engineering Books</option>
    <option value="Exam Preparation">Exam Preparation</option>
    <option value="Health, Family & Personal Development">Health, Family & Personal Development</option>
    <option value="Health, Fitness & Nutrition">Health, Fitness & Nutrition</option>
    <option value="Historical Fiction">Historical Fiction</option>
    <option value="History">History</option>
    <option value="Humour">Humour</option>
    <option value="Language, Linguistics & Writing">Language, Linguistics & Writing</option>
    <option value="Law">Law</option>
    <option value="Literature & Fiction">Literature & Fiction</option>
    <option value="Maps & Atlases">Maps & Atlases</option>
    <option value="Medicine & Health Sciences">Medicine & Health Sciences</option>
    <option value="Politics">Politics</option>
    <option value="Reference">Reference</option>
    <option value="Religion & Spirituality">Religion & Spirituality</option>
    <option value="Science & Mathematics">Science & Mathematics</option>
    <option value="Science Fiction & Fantasy">Science Fiction & Fantasy</option>
    <option value="Sciences, Technology & Medicine">Sciences, Technology & Medicine</option>
    <option value="Society & Social Sciences">Society & Social Sciences</option>
    <option value="Sports">Sports</option>
    <option value="Teen & Young Adult">Teen & Young Adult</option>
    <option value="Textbooks & Study Guides">Textbooks & Study Guides</option>
    <option value="Travel & Tourism">Travel & Tourism</option>
</select>

                                            @error('genre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="manufacturer" class="form-label d-flex ">{{ __('Manufacturer') }} <span class="text-danger">*</span></label>
                                            <input style="background-color: #e9f85c;" id="manufacturer" type="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" name="manufacturer" value="As Per Publisher" autocomplete="manufacturer" autofocus placeholder="Manufacturer">
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
                                            <label for="importer" class="form-label d-flex ">{{ __('Importer') }} <span class="text-danger">*</span></label>
                                            <input style="background-color: #e9f85c;" id="importer" type="importer" class="form-control @error('importer') is-invalid @enderror" name="importer" value="Not Applicable" autocomplete="importer" autofocus placeholder="Importer">
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
                                            <label for="packer" class="form-label d-flex ">{{ __('Packer') }} <span class="text-danger">*</span></label>
                                            <input style="background-color: #e9f85c;" id="packer" type="packer" class="form-control @error('packer') is-invalid @enderror" name="packer" value="Fullfilled by Supplier" autocomplete="packer" autofocus placeholder="Packer">
                                            <span class="error-message packer" style="color:red;"></span>

                                            @error('packer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="">
                                            <label for="url" class="form-label d-flex ">{{ __('Insta Mojo URL') }} <span onclick="copyLink()" id='copylink' style="cursor:pointer;">Copy</span> <span class="text-danger">*</span></label>
                                            <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" autocomplete="url" autofocus placeholder="Insta Mojo url">
                                            <span class="error-message url" style="color:red;"></span>

                                            @error('url')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="" >
                                            <div class="">
                                                <label for="url" class="form-label">{{ __('Main Image URL') }}</label>
                                                <input id="base_url" type="text" class="form-control @error('images') is-invalid @enderror" name="images[]" autocomplete="images" autofocus placeholder="Base URL">
                                                <span class="error-message images[]" style="color:red;"></span>

                                                @error('images')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <button type='button' id='addFileInput' class="btn btn-primary mt-2">Add More Images</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-right mb-2 d-flex" id='preview' style="justify-content: flex-end;" >
                                <div style="border: 2px solid #ccc;width: 300px;max-height: 300px;height:300px;">
                                    <img src="" id='previewImage' />
                                    <div class='image-status' style="text-align: center;padding: 5px;display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div style="text-align: right;margin-top: 30px;">
                            <button type="submit" class="btn btn-warning float-right" id='draft'>Save as Draft</button>
                            <button type="submit" class="btn btn-success float-right">Publish to Website</button>
                        </div>
                    </div>
                </div>
            </form>
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


<script>
   

</script>

@endpush