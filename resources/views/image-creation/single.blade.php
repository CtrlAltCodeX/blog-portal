@extends('layouts.master')

@section('title', __('Single Image Creation'))

@php
if(request()->maker == 'wo-watermark'){
    $formRoute = "/admin/convert/image";
} elseif(request()->maker == 'wo-watermark'){
    $formRoute = "/admin/images/watermark/store";
}
@endphp

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">
                    Single Image Maker
                </h4>
            </div>
            <div class="card-body pt-0">
                <div class="form-group">
                    <div id="fileInputContainer">
                        <div class="form-group"></div>
                        <div class="d-flex justify-content-end">
                            <!-- <label for="fileInput1" class="mt-2">Single Image Maker</label> -->
                            <form action="" method="get" id='form'>
                                <div class="d-flex">
                                    <div class="d-flex align-items-baseline">
                                        <input type="radio" class="m-2" name="maker" id="w-watermark" value='w-watermark' {{ request()->maker == 'w-watermark' ?  'checked' : '' }} />
                                        <label for="w-watermark">With Watermark<br />
                                            <small class="text-danger">( GMC Not Approved )</small>
                                        </label>
                                    </div>

                                    <div class="d-flex align-items-baseline">
                                        <input type="radio" class="m-2" name="maker" id="wo-watermark" value='wo-watermark' {{ request()->maker == 'wo-watermark' ?  'checked' : '' }} />
                                        <label for="wo-watermark">Without Watermark<br />
                                            <small class="text-danger">( GMC Approved )</small>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <form action="{{ route('image.watermark.store')  }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-2" @error('file') style="border: red 2px dotted;" @enderror>
                                @if(request()->maker == 'wo-watermark')
                                <label for="fileInput1">Image without Watermark<span class="text-danger">*</span></label>

                                <input type="file" class="dropify @error('file') is-invalid @enderror" name="file" multiple>
                                <div id='multiImagesDownload' style="display: none;">
                                    <a href='#' class="w-100 d-flex justify-content-end my-4">
                                        <img src="/downlod-icon.png" />
                                    </a>
                                </div>
                                @endif

                                @if(request()->maker == 'w-watermark')
                                <label for="fileInput1">Image with Watermark<span class="text-danger">*</span></label>

                                <input type='hidden' name='with_watermark' value=1 />
                                <div class="form-group">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-group mb-0 @error('file') is-invalid @enderror" @error('file') style="border: red 2px dotted;" @enderror>
                                        <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="file" name="file" />
                                    </div>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            <div class="w-100 d-flex flex-column align-items-center" style="grid-gap: 10px;">
                                <div class="d-flex w-100 align-items-center" style="grid-gap:10px;">
                                    <input type="text" class="form-control image-url" disabled placeholder="Click button to Generate URL" />
                                    <div class="d-flex" style="grid-gap: 10px;">
                                        <button type="button" class="btn btn-primary btn-sm" id="refresh" data-session='watermarkFileUrl'>Generate URL</button>
                                        <button type="button" class="btn btn-primary btn-sm copy">Copy URL</button>
                                        <a href="{{ url('/') }}/storage/uploads/{{session()->get('watermarkFileUrl')}}" download class="btn btn-primary btn-sm" id='download' style="width: 100px;;">Download</a>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Convert & Download</button>
                            </div>
                        </form>
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