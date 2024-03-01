@extends('layouts.master')

@section('title', __('Watermark'))

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">{{ __('watermark') }}</h1>

            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Watermark') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('watermark') }}</li>
                </ol>
            </div>
        </div>

        <!-- Row -->
        <form action="{{ route('watermark.store') }}" method="POST" enctype='multipart/form-data'>
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
                                <label for="title" class="form-label">{{ __('Title') }}<span
                                        class="text-danger">*</span></label>
                                <input id="title" type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" title="title"
                                    autocomplete="title" autofocus placeholder="Title">

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fileInput1">Watermark Image<span class="text-danger">*</span></label>

                                <div class="form-group mb-0 @error('file') is-invalid @enderror"
                                    @error('file') style="border: red 2px dotted;" @enderror>
                                    <input type="file" class="dropify @error('images') is-invalid @enderror"
                                        data-bs-height="180" id="file" name="file" />
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
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
@endpush
