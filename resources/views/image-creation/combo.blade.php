@extends('layouts.master')

@section('title', __('Combo Image Creation'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">
                    Combo Image Maker
                </h4>
            </div>
            <div class="card-body pt-0">
                <div class="form-group">
                    <div id="fileInputContainer">
                        <div class="form-group"></div>
                        <div class="d-flex justify-content-end">
                            <!-- <label for="fileInput1" class="mt-2">Images<span class="text-danger">*</span>( Multiple Images )</label> -->
                            <form action="" method="get" id='form'>
                                <div>
                                    <input type="radio" class="m-2" name="maker" id="with-watermark" value='w-watermark' {{ request()->maker == 'w-watermark' ?  'checked' : '' }} />
                                    <label for="with-watermark">With Watermark</label>

                                    <input type="radio" class="m-2" name="maker" id="without-watermark" value='wo-watermark' {{ request()->maker == 'wo-watermark' ?  'checked' : '' }} />
                                    <label for="without-watermark">Without Watermark</label>
                                </div>
                            </form>
                        </div>

                        <form action="{{ route('image.collage.store') }}" method="post" enctype="multipart/form-data" id="{{ request()->maker == 'wo-watermark' ? 'multipleImagesform': '' }}">
                            @csrf
                            <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                @if(request()->maker == 'wo-watermark')
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <div class="d-flex" style="grid-gap: 10px;">
                                            <label for="fileInput1">Image without Watermark<span class="text-danger">*</span></label>
                                            <p id="selectedCount"></p>
                                        </div>
                                        <!-- <label for="fileInput1">Multiple Images<span class="text-danger">*</span></label> -->
                                        <!-- <button class="btn btn-primary" type="submit" id='convert'>Convert</button> -->
                                    </div>

                                    <div class="form-group mb-0 @error('file') is-invalid @enderror" @error('file') style="border: red 2px dotted;" @enderror>
                                        <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="file" name="file[]" multiple />
                                    </div>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif

                                @if(request()->maker == 'w-watermark')

                                <div class="form-group">
                                    <div class="d-flex justify-content-between" style="grid-gap: 10px;">
                                        <label for="fileInput1">Image with Watermark<span class="text-danger">*</span></label>
                                        <p id="selectedCount"></p>
                                    </div>

                                    <input name="is_with_watermark" type="hidden" value=1 />
                                    <div class="form-group mb-0 @error('file') is-invalid @enderror" @error('file') style="border: red 2px dotted;" @enderror>
                                        <input type="file" class="dropify @error('images') is-invalid @enderror" data-bs-height="180" id="file" name="file[]" multiple />
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

@include('image-creation.script')

<script>
    $(document).ready(function() {
        $('#file').on('change', function() {
            var selectedImages = $(this)[0].files.length;
            $('#selectedCount').text(selectedImages + " Images selected");
        });
    })
</script>

@endpush