@extends('layouts.master')

@section('title', __('Single Image Creation'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <div id="fileInputContainer">
                        <div class="form-group"></div>
                        <div class="d-flex justify-content-between">
                            <label for="fileInput1" class="mt-2">Images<span class="text-danger">*</span>( Multiple Images )</label>
                            <form action="" method="get" id='form'>
                                <div>
                                    <input type="radio" class="m-2" name="maker" id="w-watermark" value='w-watermark' {{ request()->maker == 'w-watermark' ?  'checked' : '' }} />
                                    <label for="w-watermark">With Watermark</label>

                                    <input type="radio" class="m-2" name="maker" id="wo-watermark" value='wo-watermark' {{ request()->maker == 'wo-watermark' ?  'checked' : '' }} />
                                    <label for="wo-watermark">Without Watermark</label>
                                </div>
                            </form>
                        </div>

                        <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                            @if(request()->maker == 'wo-watermark')
                                <form action="{{ route('convert.image') }}" method="post" enctype="multipart/form-data" id='multipleImagesform'>
                                    @csrf
                                    <input id="multipleFiles" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                    <div id='multiImagesDownload' style="display: none;">
                                        <a href='#' class="w-100 d-flex justify-content-end my-4" id='downloadMultipleImage'>
                                            <img src="/downlod-icon.png" />
                                        </a>
                                    </div>
                                </form>
                            @endif

                            @if(request()->maker == 'w-watermark')
                                <form action="{{ route('image.watermark.store') }}" method="POST" enctype='multipart/form-data'>
                                    @csrf
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label for="title" class="form-label">{{ __('Title') }}<span class="text-danger">*</span></label>
                                            <button class="btn btn-primary mb-2">Convert</button>
                                        </div>
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
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- INTERNAL File-Uploads Js-->
<script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>

@include('image-creation.script');

@endpush