@extends('layouts.master')

@section('title', __('Create Developer'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Developer') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Developers') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Create Developer') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('developers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create Developer') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="name" class="form-label">{{ __('Candidate Name') }}<span class='text-danger'>*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Name">

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-3">
                                <label for="email" class="form-label">{{ __('Email') }}<span class='text-danger'>*</span></label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="mobile" class="form-label">{{ __('Phone No.') }}<span class='text-danger'>*</span></label>
                                <input id="mobile" type="number" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" autocomplete="mobile" autofocus placeholder="Mobile....">

                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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
