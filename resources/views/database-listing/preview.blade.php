@extends('layouts.master')

@section('title', __('Create New Listing'))

@push('css')
<style>
    hr {
        border: 1px solid #ccc;
        width: 100%;
        height: 0px !important;
        margin-top: 0px;
    }

    .alert-msg {
        background-color: grey;
        color: white;
    }
   table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;
}
</style>
@endpush

@section('content')
<table style="width:100%">
    <tr>
        <th>Fields</th>
        <th>Result</th> 
    
    </tr>
    <tr>
        <td>{{ __('Product Title') }}</td>
        <td>@if(isset($data['title']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Product Description</td>
        <td>@if(isset($data['description']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>MRP</td>
        <td>@if(isset($data['mrp']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Discount ( % )</td>
        <td>@if(isset($data['discount']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Selling Price</td>
        <td>@if(isset($data['selling_price']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Publisher</td>
        <td>@if(isset($data['publication']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Author Name</td>
        <td>@if(isset($data['author_name']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Edition</td>
        <td>@if(isset($data['edition']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Category</td>
        <td>@if(isset($data['label']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>

        <td>SKU</td>
        <td>@if(isset($data['sku']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Language</td>
        <td>@if(isset($data['language']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>No. of Pages</td>
        <td>@if(isset($data['pages']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Condition</td>
        <td>@if(isset($data['condition']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Binding Type</td>
        <td>@if(isset($data['binding']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Insta Mojo URL</td>
        <td>@if(isset($data['url']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
    <tr>
        <td>Main Image URL</td>
        <td>@if(isset($data['images']))<i class="fa fa-check" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
    </tr>
  
</table>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- INTERNAL File-Uploads Js-->
<script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>

@include('listing.script')

<script>
    $(document).ready(function() {
        $('#fileInput1').change(function() {
            $("#singleImageDownload").show();
        })

        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        })

        $('#downloadImage').click(function() {
            $('#singleImageForm').submit();
        })

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        });

        
    })
</script>
@endpush