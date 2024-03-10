@extends('layouts.master')

@section('title', __('Single Image Creation'))

@section('content')
<div class="main-container container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div id="fileInputContainer">
                            <div class="form-group"></div>
                            <label for="fileInput1" class="mt-2">Images<span class="text-danger">*</span>( Multiple Images )</label>
                            <div class="form-group mt-2" @error('multipleImages') style="border: red 2px dotted;" @enderror>
                                <form action="{{ route('convert.image') }}" method="post" enctype="multipart/form-data" id='multipleImagesform'>
                                    @csrf
                                    <input id="multipleFiles" type="file" class="dropify @error('multipleImages') is-invalid @enderror" name="multipleImages[]" multiple>
                                    <div id='multiImagesDownload' style="display: none;">
                                        <a href='#' class="w-100 d-flex justify-content-end my-4" id='downloadMultipleImage'>
                                            <img src="/downlod-icon.png" />
                                        </a>
                                    </div>
                                </form>
                            </div>
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

<script>
    $(document).ready(function() {
        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        });

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        })
    })
</script>
@endpush