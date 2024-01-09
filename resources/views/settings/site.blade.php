@extends('layouts.master')

@section('title', __('Settings'))

@section('content')
<div class="card mt-5">
    <div class="card-body">
        <div>
            <form action="{{ route('settings.site.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Blog Url') }}<span class="text-danger">*</span></label>

                    <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $siteSettings->url }}" autofocus placeholder="Blog Url">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Logo') }}<span class="text-danger">*</span></label>

                    <input id="logo" type="file" class="form-control @error('logo') is-invalid @enderror" name="logo" value="{{ $siteSettings->logo }}" autofocus placeholder="Logo">
                </div>

                <button class="btn btn-primary mt-5" id="refresh_token">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
