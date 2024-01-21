@extends('layouts.master')

@section('title', __('Find Products'))

@section('content')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Find Products ( Using URL )') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Find Products ( Using URL )') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Find Products ( Using URL )') }}</li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <form action="{{ route('amazon.find.products') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Automatic Listing ( Using URL )') }}
                        </h4>
                    </div>
                    <div class="card-body text-end">
                        <div>
                            <div class="form-group">
                                <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search') }}" autocomplete="name" autofocus placeholder="Find Product using URL">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success float-right">Search Now</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End Row -->
</div>


@endsection