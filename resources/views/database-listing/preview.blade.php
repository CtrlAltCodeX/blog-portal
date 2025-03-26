@extends('layouts.master')

@section('title', __('Results'))

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

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h2 style="text-align: center;"><u>Practice Mode Report Card</u></h2>
        <p style="text-align: justify;"><strong>Note: </strong> Below is the report card for your practice mode. The sections marked with a red cross indicate that you did not accurately fill the fields according to the video guidelines. However, The sections marked in green suggest that your entries may be correct.
            Apart from this, to enhance your knowledge and performance, we recommend watching the video tutorials at least 2 to 3 times for a better understanding of how to perfect your inputs. Please note that your listing will still be reviewed during the Final Selection Process.
            <br /><br />
            <strong>टिप्पणी:</strong> नीचे आपके अभ्यास मोड का रिपोर्ट कार्ड है। लाल क्रॉस से चिह्नित अनुभाग इंगित करते हैं कि आपने वीडियो दिशानिर्देशों के अनुसार फ़ील्ड को सही ढंग से नहीं भरा है। हालाँकि, हरे रंग में चिह्नित अनुभाग सुझाव देते हैं कि आपकी प्रविष्टियाँ सही हो सकती हैं।
            इसके अलावा, आपके ज्ञान और प्रदर्शन को बढ़ाने के लिए, हम आपके इनपुट को बेहतर बनाने के तरीके को बेहतर ढंग से समझने के लिए वीडियो ट्यूटोरियल को कम से कम 2 से 3 बार देखने की सलाह देते हैं। कृपया ध्यान दें कि अंतिम चयन प्रक्रिया के दौरान भी आपकी सूची की समीक्षा की जाएगी।
            <br /><br />
            <strong>দ্রষ্টব্য:</strong> নীচে আপনার অনুশীলন মোড জন্য রিপোর্ট কার্ড. একটি লাল ক্রস দ্বারা চিহ্নিত বিভাগগুলি নির্দেশ করে যে আপনি ভিডিও নির্দেশিকা অনুযায়ী সঠিকভাবে ক্ষেত্রগুলি পূরণ করেননি৷ যাইহোক, সবুজ রঙে চিহ্নিত বিভাগগুলি সুপারিশ করে যে আপনার এন্ট্রি সঠিক হতে পারে।
            এছাড়াও, আপনার জ্ঞান এবং কর্মক্ষমতা বাড়াতে, আপনার ইনপুটগুলি কীভাবে নিখুঁত করবেন তা আরও ভালভাবে বোঝার জন্য আমরা কমপক্ষে 2 থেকে 3 বার ভিডিও টিউটোরিয়াল দেখার পরামর্শ দিই। অনুগ্রহ করে মনে রাখবেন যে চূড়ান্ত নির্বাচন প্রক্রিয়া চলাকালীন আপনার তালিকা এখনও পর্যালোচনা করা হবে।
        </p>
        <h3 style="text-align: center;"><u>Results Based on Your Performance</u></h3>
        <div style="width: 100%;display:flex;justify-content:center;grid-gap: 20px;">
            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>

                </tr>
                <tr>
                    <td>{{ __('Product Title') }}</td>
                    <td>@if(isset($data['title']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Product Description</td>
                    <td>@if(isset($data['description']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>MRP</td>
                    <td>@if(isset($data['mrp']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Discount ( % )</td>
                    <td>@if(isset($data['discount']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>

            </table>

            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>

                </tr>
                <tr>
                    <td>Selling Price</td>
                    <td>@if(isset($data['selling_price']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Publisher Name</td>
                    <td>@if(isset($data['publication']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Author Name</td>
                    <td>@if(isset($data['author_name']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Edition</td>
                    <td>@if(isset($data['edition']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
            </table>

            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>

                </tr>
                <tr>
                    <td>Category</td>
                    <td>@if(isset($data['label']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>SKU</td>
                    <td>@if(isset($data['sku']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Language</td>
                    <td>@if(isset($data['language']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>No. of Pages</td>
                    <td>@if(isset($data['pages']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
            </table>

            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>
                </tr>
                <tr>
                    <td>Product Condition</td>
                    <td>@if(isset($data['condition']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Binding Type</td>
                    <td>@if(isset($data['binding']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Insta Mojo URL</td>
                    <td>@if(isset($data['url']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Main Image URL</td>
                    <td>@if(isset($data['images']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
            </table>

            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>
                </tr>
                <tr>
                    <td>ISBN 10</td>
                    <td>@if(isset($data['isbn_10']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>ISBN 13</td>
                    <td>@if(isset($data['isbn_13']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Publish Year</td>
                    <td>@if(isset($data['publish_year']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Weight</td>
                    <td>@if(isset($data['weight']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
            </table>

            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>
                </tr>
                <tr>
                    <td>Reading Age</td>
                    <td>@if(isset($data['reading_age']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Country Origin</td>
                    <td>@if(isset($data['country_origin']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Genre</td>
                    <td>@if(isset($data['genre']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Manufacturer</td>
                    <td>@if(isset($data['manufacturer']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
            </table>

            <table style="width:25%">
                <tr>
                    <th style="background-color:#eee;">Fields</th>
                    <th style="background-color:#eee;">Result</th>
                </tr>
                <tr>
                    <td>Importer</td>
                    <td>@if(isset($data['importer']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
                <tr>
                    <td>Packer</td>
                    <td>@if(isset($data['packer']))<i class="fa fa-check text-success" style="font-size:36px"></i>@else <i class="fa fa-close" style="font-size:36px;color:red"></i> @endif</td>
                </tr>
            </table>
        </div>

        <div class="d-flex mt-5 justify-content-center">
            <a href="{{ route('database-listing.create')  }}" class="btn btn-success">Continue Practice</a>
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