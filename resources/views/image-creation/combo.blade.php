@extends('layouts.master')

@section('title', __('Combo Image Creation'))

@section('content')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <form action="" method="get" id='form'>
                            <label>Select Maker</label>
                            <select class="form-control" id='method' name="maker">
                                <option value="">--Select--</option>
                                <option {{ request()->maker == 'watermark' ?  'selected' : '' }} value="watermark">With Watermark</option>
                                <option {{ request()->maker == 'combo' ?  'selected' : '' }} value="combo">Without Watermark</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center overflow-scroll">
                            @foreach($images as $key => $image)
                            @if($key <= 5) 
                            <div class='d-flex flex-column m-2' style="width: 50%;">
                                <img src="/storage/uploads/{{$image}}" width="100" />
                                <div class="mt-2 d-flex justify-content-between">
                                    <img src="/downlod-icon.png" title="Copy URL" />
                                    <img src="/downlod-icon.png" title="Download" />
                                </div>
                            </div>
                            @endif
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row -->
@if(request()->maker == 'watermark')
<form action="{{ route('image.watermark.store') }}" method="POST" enctype='multipart/form-data'>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        {{ __('Watermark') }}
                    </h4>

                    <button type="submit" class="btn btn-primary float-right">Convert</button>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">{{ __('Title') }}<span class="text-danger">*</span></label>
                        <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" title="title" autocomplete="title" autofocus placeholder="Title">

                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fileInput1">Watermark Image<span class="text-danger">*</span></label>

                        <div class="form-group mb-0 @error('file') is-invalid @enderror" @error('file') style="border: red 2px dotted;" @enderror>
                            <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="file" name="file" />
                        </div>
                        @error('file')
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
@endif

@if(request()->maker == 'combo')
<form action="{{ route('image.collage.store') }}" method="POST" enctype='multipart/form-data'>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        {{ __('Combo Image Maker') }}
                    </h4>

                    <button type="submit" class="btn btn-primary float-right">Convert</button>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="fileInput1">Multiple Images<span class="text-danger">*</span></label>

                        <div class="form-group mb-0 @error('file') is-invalid @enderror" @error('file') style="border: red 2px dotted;" @enderror>
                            <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="file" name="file[]" multiple />
                        </div>
                        @error('file')
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
@endif
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
        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        });

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        });

        $("#method").change(function() {
            $("#form").submit();
        })

    })
</script>
@endpush