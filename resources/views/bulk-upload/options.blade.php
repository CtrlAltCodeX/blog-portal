@extends('layouts.master')

@section('title', __('Upload CSV file'))

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Bulk Upload</h3>
                    <a href="{{route('view.upload')}}" class="btn btn-info"><i class="fa fa-eye"></i>View Uploaded List</a>
                </div>

                <div class="card-body">
                    <form action="" id='listingform'>
                        <label for="description" class="form-label d-flex justify-content-between text-danger" style="margin-top: -10px;">
                            <div>{{ __('File Type') }}</div>
                        </label>
                        <select class="form-control select2 w-50" name="filetype" id='type'>
                            <option value="">Select</option>
                            <option value=1 {{ request()->filetype == 1 ? 'selected' : '' }}>Create Listing</option>
                            <option value=2 {{ request()->filetype == 2 ? 'selected' : '' }}>Edit Listing</option>
                        </select>
                    </form>

                    @if(request()->filetype == 1)
                    <form action="{{ route('get-upload-file.options') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div id="fileInputContainer">
                                <div class="form-group"></div>
                                <div class="d-flex justify-content-between">
                                    <label for="fileInput1" class="mt-2">File Upload<span class="text-danger">*</span></label>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                                <div class="form-group mt-2" @error('csv_file') style="border: red 2px dotted;" @enderror>
                                    <input type="file" class="dropify @error('csv_file') is-invalid @enderror" name="csv_file">
                                </div>
                            </div>
                        </div>
                    </form>
                    @elseif(request()->filetype == 2)
                    <br><br>
                    <h4>Under Process</h4>
                    @endif
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
        $("#type").on("change", function() {
            $("#listingform").submit();
        });
    });
</script>
@endpush