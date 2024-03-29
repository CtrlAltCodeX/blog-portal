@extends('layouts.master')

@section('title', __('Settings'))

@section('content')
@can('Settings -> Site Access')
<div class="card mt-5">
    <div class="card-body">
        <div>
            <form action="{{ route('settings.site.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Blog Base Url') }}<span class="text-danger">*</span></label>

                    <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $siteSettings->url??"" }}" autofocus placeholder="Blog Url">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Watermark text') }}<span class="text-danger">*</span></label>

                    <input id="watermark_text" type="text" class="form-control @error('watermark_text') is-invalid @enderror" name="watermark_text" value="{{ $siteSettings->watermark_text??"" }}" autofocus placeholder="Watermark Text">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Logo') }}<span class="text-danger">*</span></label>
                    <div class="row mb-5">
                        <div class="col-lg-3">
                            <input type="file" class="dropify" data-bs-height="180" id="logo" name="logo" data-default-file="/storage/{{ $siteSettings->logo??"" }}" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Homepage Image') }}<span class="text-danger">*</span></label>
                    <div class="row mb-5">
                        <div class="col-lg-3">
                            <input type="file" class="dropify" data-bs-height="180" id="homepage_image" name="homepage_image" data-default-file="/storage/{{ $siteSettings->homepage_image??"" }}" />
                        </div>
                    </div>
                </div>

                @can('Settings -> Site Update')
                <button class="btn btn-primary mt-5" id="refresh_token">Save</button>
                @endcan
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- INTERNAL File-Uploads Js-->
<script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

@endpush