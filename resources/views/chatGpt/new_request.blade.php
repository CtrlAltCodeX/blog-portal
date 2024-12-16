@extends('layouts.master')

@section('title', __('EXAM360 AI Bots'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('EXAM360 AI Bots') }}</h1>

        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('EXAM360 AI Bots') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('EXAM360 AI Bots') }}</li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <form action="{{ route('getai.response') }}" method="POST" enctype='multipart/form-data'>
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ __('EXAM360 AI Bots') }}
                        </h4>

                    </div>
                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <div class="d-flex justify-content-between mb-2 align-items-center">
                                    <label for="name" class="form-label">{{ __('What can I help with?') }}</label>
                                    <div style="text-align: right;margin-top:20px;">
                                        <a href="{{ route('ai.description') }}" id="clear_btn">Clear</a>
                                        <a id="copy_btn" class="m-2">Copy</a>
                                    </div>
                                </div>

                                <textarea class="form-control" required placeholder="Message here .." name="product_info" required id="request">@if(isset($user_request)) {{$user_request}}@endif</textarea>
                                @error('product_info')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            @if(!empty($assistent_response))
                            <strong>Results: </strong>
                            <div class="form-group">
                                <textarea class="form-control" id="response" readonly rows="10">{!! $assistent_response['content'] !!}</textarea>
                            </div>
                            @endif
                        </div>
                        <div style="width: 100%; text-align:right;">
                            <button type="submit" class="btn btn-success">Find and Give Results</button>
                        </div>

                        <br /><br />
                        <div style="background-color: #eee;padding: 20px;color: black;">
                            <h3 style="text-align:center;"><u>FREQUENTLY SEARCH:</u></h3> <br />
                            <span style="text-align:justify;font-size:18px;"> Q. Write Product Description in 300 to 400 words of the Book Name - ______.</span> <br>
                            <span style="text-align:justify;font-size:18px;">Q. Write Top 20 Google Search Keywords Which is Most Searchable by Users Before Buying this Item of the Book Name - ____.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')

<script type="text/javascript">
    $("#clear_btn").on("click", function() {
        $("#request").html('');
        $("#response").html('');
    });

    $("#copy_btn").on("click", function() {
        var copyText = document.getElementById("response");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        alert("Copied the text: " + copyText.value);
    });
</script>
@endpush