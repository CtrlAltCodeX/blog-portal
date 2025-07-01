@extends('layouts.master')

@section('title', $user->name)

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Update User') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Users') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Update User') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('developers.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update User') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">{{ __('Save') }}</button>
                    </div>

                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="form-group col-3">
                                    <label for="name" class="form-label">{{ __('Candidate Name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}" autocomplete="name" autofocus placeholder="Name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>

                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $user->email }}" autocomplete="email" placeholder="Email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="mobile" class="form-label">{{ __('Phone No.') }}</label>
                                    <input id="mobile" type="number" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') ?? $user->mobile }}" autocomplete="mobile" autofocus placeholder="Mobile....">

                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox"
                                               id="disable_api_key"
                                               name="disable_api_key"
                                               value="1"
                                               {{ is_null($user->api_key) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disable_api_key">
                                            Disable API Key
                                        </label>
                                    </div>

                                    @error('disable_api_key')
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
        </div>
    </form>
    <!-- End Row -->
</div>
@endsection
