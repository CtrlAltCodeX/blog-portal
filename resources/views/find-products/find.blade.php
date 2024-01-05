@extends('layouts.master')

@section('title', __('Find Products'))

@section('content')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Find Products') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Find Products') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Find Products') }}</li>
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
                            {{ __('Search') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search') }}" autocomplete="name" autofocus placeholder="Search....">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End Row -->
</div>


@endsection