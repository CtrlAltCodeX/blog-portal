@extends('layouts.master')

@section('title', __('Create User'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Change Password') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Change Password') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Change Password') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('update.user.password') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Change Password') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="current" class="form-label">{{ __('Current Password') }}</label>
                                <input id="current" type="password" class="form-control @error('current') is-invalid @enderror" name="current" value="{{ old('current') }}" autocomplete="current" autofocus placeholder="Current Password">

                                @error('current')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="new" value="{{ old('new') }}" autocomplete="new" placeholder="New Password">

                                @error('new')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="confirm" type="number" class="form-control @error('confirm') is-invalid @enderror" name="confirm" value="{{ old('confirm') }}" autocomplete="confirm" autofocus placeholder="Confirm Password">

                                @error('confirm')
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

@push('js')
<script>
    function validatePassword() {
        var password = $("#new").val();
        var confirmPassword = $("#confirm").val();

        if (password !== confirmPassword) {
            $("#error").html('New password or Confirm does not match');
            $('.btn').attr('disabled', true);
        } else {
            $("#error").html('');
            $('.btn').attr('disabled', false);
            // Add additional logic for form submission or other actions
        }
    }
</script>
@endpush