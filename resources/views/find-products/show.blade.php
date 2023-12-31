@extends('layouts.master')

@section('title', __('Show Product Info'))

@section('content')

<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Product Info') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Find Products') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Find Products') }}</li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        {{ __('Product Info') }}
                    </h4>

                    <button type="submit" class="btn btn-primary float-right">Process</button>
                </div>
                <div class="card-body">
                    <div>
                        <div class="form-group">
                            <label><b>Name:</b> </label><span>{{ $allDetails['title'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="form-group d-flex flex-column">
                            <label>
                                <b>Images:</b>
                            </label>
                            <span>
                                <img src="{{ $allDetails['image'] }}" width="200" />
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label><b>Description:</b> </label><span>{!! $allDetails['desc'] !!}</span>
                        </div>
                    </div>
                    @if(isset($allDetails['specifications']['ASIN']))
                    <div>
                        <div class="form-group">
                            <label><b>ASIN:</b> </label><span>{{ $allDetails['specifications']['ASIN'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Publisher']))
                    <div>
                        <div class="form-group">
                            <label><b>Publisher:</b> </label><span>{{ $allDetails['specifications']['Publisher'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Language']))
                    <div>
                        <div class="form-group">
                            <label><b>Language:</b> </label><span>{{ $allDetails['specifications']['Language'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Perfect Paperback']))
                    <div>
                        <div class="form-group">
                            <label><b>Perfect </b>Paperback: </label><span>{{ $allDetails['specifications']['Perfect Paperback'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Reading age']))
                    <div>
                        <div class="form-group">
                            <label><b>Reading </b>age: </label><span>{{ $allDetails['specifications']['Reading age'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Item Weight']))
                    <div>
                        <div class="form-group">
                            <label><b>Item </b>Weight: </label><span>{{ $allDetails['specifications']['Item Weight'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Dimensions']))
                    <div>
                        <div class="form-group">
                            <label><b>Dimensions:</b> </label><span>{{ $allDetails['specifications']['Dimensions'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Country of Origin']))
                    <div>
                        <div class="form-group">
                            <label><b>Country </b>of Origin: </label><span>{{ $allDetails['specifications']['Country of Origin'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Net Quantity']))
                    <div>
                        <div class="form-group">
                            <label><b>Net </b>Quantity: </label><span>{{ $allDetails['specifications']['Net Quantity'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Packer']))
                    <div>
                        <div class="form-group">
                            <label><b>Packer:</b> </label><span>{{ $allDetails['specifications']['Packer'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Generic Name']))
                    <div>
                        <div class="form-group">
                            <label><b>Generic Name: </b></label><span>{{ $allDetails['specifications']['Generic Name'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Best Sellers Rank']))
                    <div>
                        <div class="form-group">
                            <label><b>Best Sellers Rank: </b></label><span>{{ $allDetails['specifications']['Best Sellers Rank'] }}</span>
                        </div>
                    </div>
                    @endif
                    @if(isset($allDetails['specifications']['Customer Reviews']))
                    <div>
                        <div class="form-group">
                            <label><b>Customer Reviews: </b> </label><span>{{ $allDetails['specifications']['Customer Reviews'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>

@endsection