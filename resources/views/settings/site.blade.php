@extends('layouts.master')

@section('title', __('Settings'))

@php
$buttonOne = explode(',',$siteSettings?->button_1);
$buttonTwo = explode(',',$siteSettings?->button_2);
$buttonThree = explode(',',$siteSettings?->button_3);
$buttonFour = explode(',',$siteSettings?->button_4);

@endphp

@section('content')
@can('Settings -> Site Access')
<div class="card mt-5">
    <div class="card-body">
        <div>
            <form action="{{ route('settings.site.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Blog Base Url') }}<span class="text-danger">*</span></label>

                    <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $siteSettings->url??'' }}" autofocus placeholder="Blog Url">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Watermark text') }}<span class="text-danger">*</span></label>

                    <input id="watermark_text" type="text" class="form-control @error('watermark_text') is-invalid @enderror" name="watermark_text" value="{{ $siteSettings->watermark_text??'' }}" autofocus placeholder="Watermark Text">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Calculator link') }}<span class="text-danger">*</span></label>

                    <input id="calc" type="text" class="form-control @error('calc_link') is-invalid @enderror" name="calc_link" value="{{ $siteSettings->calc_link??'' }}" autofocus placeholder="Calculator link">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Button 1') }}<span class="text-danger">*</span></label>
                    <div class="d-flex" style="grid-gap: 10px;">
                        <input id="button_1" type="text" class="form-control @error('button_1') is-invalid @enderror" name="button_1" value="{{ $buttonOne[0]??'' }}" autofocus placeholder="Button One">
                        <input id="button_1_href" type="text" class="form-control @error('button_1_href') is-invalid @enderror" name="button_1_href" value="{{ $buttonOne[1]??'' }}" autofocus placeholder="Button Link">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Button 2') }}<span class="text-danger">*</span></label>
                    <div class="d-flex" style="grid-gap: 10px;">
                        <input id="button_2" type="text" class="form-control @error('button_2') is-invalid @enderror" name="button_2" value="{{ $buttonTwo[0]??'' }}" autofocus placeholder="Button Two">
                        <input id="button_2_href" type="text" class="form-control @error('button_2_href') is-invalid @enderror" name="button_2_href" value="{{ $buttonTwo[1]??'' }}" autofocus placeholder="Button Link">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Button 3') }}<span class="text-danger">*</span></label>
                    <div class="d-flex" style="grid-gap: 10px;">
                        <input id="button_3" type="text" class="form-control @error('button_3') is-invalid @enderror" name="button_3" value="{{ $buttonThree[0]??'' }}" autofocus placeholder="Button Three">
                        <input id="button_3_href" type="text" class="form-control @error('button_3_href') is-invalid @enderror" name="button_3_href" value="{{ $buttonThree[1]??'' }}" autofocus placeholder="Button Link">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Button 4') }}<span class="text-danger">*</span></label>
                    <div class="d-flex" style="grid-gap: 10px;">
                        <input id="button_4" type="text" class="form-control @error('button_4') is-invalid @enderror" name="button_4" value="{{ $buttonFour[0]??'' }}" autofocus placeholder="Button Four">
                        <input id="button_4_href" type="text" class="form-control @error('button_4_href') is-invalid @enderror" name="button_4_href" value="{{ $buttonFour[1]??'' }}" autofocus placeholder="Button Link">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Logo') }}<span class="text-danger">*</span></label>
                    <div class="row mb-5">
                        <div class="col-lg-3">
                            <input type="file" class="dropify" data-bs-height="180" id="logo" name="logo" data-default-file="/storage/{{ $siteSettings->logo??'' }}" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Homepage Image') }}<span class="text-danger">*</span></label>
                    <div class="row mb-5">
                        <div class="col-lg-3">
                            <input type="file" class="dropify" data-bs-height="180" id="homepage_image" name="homepage_image" data-default-file="/storage/{{ $siteSettings->homepage_image??'' }}" />
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