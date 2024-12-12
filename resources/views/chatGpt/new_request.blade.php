@extends('layouts.master')

@section('title', __('AI Description'))

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">{{ __('AI Description') }}</h1>

            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('AI Description') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('AI Description') }}</li>
                </ol>
            </div>
        </div>

        <!-- Row -->
        <form action="{{ route('get_ai_response') }}" method="POST" enctype='multipart/form-data'>
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">
                                {{ __('AI Description') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div>
                                
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('What can I help with?') }}</label>
                                    <textarea class="form-control" required placeholder="Message here .." name="product_info" required id="request">@if(isset($user_request)) {{$user_request}}@endif</textarea>
                                    @error('product_info')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @if(!empty($assistent_response))
                                    <div class="form-group">
                                        <textarea class="form-control" id="response">{!! $assistent_response['content'] !!}</textarea>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <div style="text-align: right;margin-top:20px ;">
                                <a href="{{ route('ai_description') }}" class="btn btn-danger float-right" id="clear_btn">Clear</a>
                                <button class="btn btn-dark float-right" id="copy_btn">Copy</button>
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
    $("#clear_btn").on("click",function(){
        $("#request").html('');
        $("#response").html('');
    });

    $("#copy_btn").on("click",function(){
        var copyText = document.getElementById("response");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        alert("Copied the text: " + copyText.value);
    });
</script>
@endpush
