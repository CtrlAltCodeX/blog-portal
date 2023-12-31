@extends('layouts.master')

@section('title', __('Update Listing'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Update Listing') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Listing') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Update Listing') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('listing.update', $post->id) }}" method="POST" enctype='multipart/form-data'>
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update Listing') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>

                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? $post->title }}" autocomplete="title" autofocus placeholder="title">

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Description" rows="10">{{ old('description') ?? $allInfo['desc'] }}</textarea>

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
                        <div class="form-group">
                            <div id="fileInputContainer">
                                <div class="form-group">
                                    <label for="fileInput1">Images*</label>
                                    @foreach($allInfo['image1'] as $key => $image)
                                    <div class="input-group{{$key}} my-2">
                                        <input type="file" class="form-control-file @error('images') is-invalid @enderror" id="fileInput1" name="images[]" value="{{ $image }}">

                                        <div class="input-group-append pt-2">
                                            <button class="btn btn-danger btn-sm removeFileInput" id={{$key}}>Remove</button>
                                        </div>

                                        @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <button id="addFileInput" type="button" class="btn btn-primary">Add File Input</button>
                            <br /><br />
                            @foreach($allInfo['image1'] as $image)
                            <img src="{{$image}}" width="200">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="selling_price" class="form-label">{{ __('Selling Price*') }}</label>
                            <input id="selling_price" type="number" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" value="{{ old('selling_price') ?? $allInfo['selling']  }}" autocomplete="selling_price" autofocus placeholder="Selling Price">

                            @error('selling_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mrp" class="form-label">{{ __('MRP*') }}</label>
                            <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') ?? $allInfo['mrp']}}" autocomplete="mrp" autofocus placeholder="MRP">

                            @error('mrp')
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
                            <label for="publication" class="form-label">{{ __('Publication*') }}</label>
                            <input id="publication" type="text" class="form-control @error('publication') is-invalid @enderror" name="publication" value="{{ old('publication') ?? $allInfo['publication'] }}" autocomplete="publication" autofocus placeholder="Publication">

                            @error('publication')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="author_name" class="form-label">{{ __('Author Name*') }}</label>
                            <input id="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name') ?? $allInfo['author_name'] }}" autocomplete="author_name" autofocus placeholder="Author name">

                            @error('author_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="about_author" class="form-label">{{ __('About Author*') }}</label>
                            <textarea id="about_author" class="form-control @error('about_author') is-invalid @enderror" name="about_author" autocomplete="about_author" autofocus placeholder="About Author Name" rows="5">{{ old('about_author') ?? $allInfo['author'] }}</textarea>

                            @error('about_author')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="edition" class="form-label">{{ __('Edition') }}</label>
                            <input id="edition" type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition') ?? $allInfo['edition']  }}" autocomplete="edition" autofocus placeholder="Edition">

                            @error('edition')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="search_key" class="form-label">{{ __('Search Key*') }}</label>
                            <textarea id="search_key" class="form-control @error('search_key') is-invalid @enderror" name="search_key" autocomplete="search_key" autofocus placeholder="Search Key" rows="5">{{ old('search_key') ?? $allInfo['search_key'] }}</textarea>

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
                            <select class="form-control select2  @error('label') is-invalid @enderror" data-placeholder="Choose Label" multiple value="{{ old('label') }}" name="label[]">
                                @foreach($categories as $category)
                                <option value="{{ $category['term'] }}" @foreach($labels as $label) @if($category['term']==$label) selected @endif @endforeach>
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

                        <div class="form-group">
                            <label for="sku" class="form-label">{{ __('SKU*') }}</label>
                            <input id="sku" type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku') ?? $allInfo['sku'] }}" autocomplete="sku" autofocus placeholder="SKU">

                            @error('sku')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="medium" class="form-label">{{ __('Medium') }}</label>
                            <input id="medium" type="text" class="form-control @error('medium') is-invalid @enderror" name="medium" value="{{ old('medium') }}" autocomplete="medium" autofocus placeholder="Medium">

                            @error('medium')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pages" class="form-label">{{ __('No. of Pages') }}</label>
                            <input id="pages" type="number" class="form-control @error('pages') is-invalid @enderror" name="pages" value="{{ old('pages') ?? $allInfo['page_no'] }}" autocomplete="pages" autofocus placeholder="No. of Pages">

                            @error('pages')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="weight" class="form-label">{{ __('Weight') }}</label>
                            <input id="weight" type="text" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ old('weight') ?? $allInfo['weight'] }}" autocomplete="weight" autofocus placeholder="Weight">

                            @error('weight')
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
                            <label for="country_origin" class="form-label">{{ __('Country of Origin*') }}</label>
                            <input id="country_origin" type="text" class="form-control @error('country_origin') is-invalid @enderror" name="country_origin" value="{{ old('country_origin') ?? 'India' }}" autocomplete="country_origin" autofocus placeholder="Country of Origin">

                            @error('country_origin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
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

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="isbn_10" class="form-label">{{ __('ISBN 10') }}</label>
                            <input id="isbn_10" type="text" class="form-control @error('isbn_10') is-invalid @enderror" name="isbn_10" value="{{ old('isbn_10') }}" autocomplete="isbn_10" autofocus placeholder="ISBN 10">

                            @error('isbn_10')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="isbn_13" class="form-label">{{ __('ISBN 13') }}</label>
                            <input id="isbn_13" type="text" class="form-control @error('isbn_13') is-invalid @enderror" name="isbn_13" value="{{ old('isbn_13') }}" autocomplete="isbn_13" autofocus placeholder="ISBN 13">

                            @error('isbn_13')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End Row -->
</div>
@endsection

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
        $("#fileInputContainer").on("click", ".removeFileInput", function(e) {
            var getId = $(this).attr('id');
            console.log(getId);
            e.preventDefault();
            console.log($("#fileInputContainer .input-group" + getId).length);
            // Check if there's more than one file input field before removing
            if ($("#fileInputContainer .input-group" + getId).length > 0) {
                $(this).closest('.input-group' + getId).remove();
            }
        });
    });
</script>
@endpush