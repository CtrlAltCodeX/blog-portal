@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <span class="login100-form-title pb-5">
            Register
        </span>

        <div class="panel panel-primary">
            <div class="panel-body tabs-menu-body p-0 pt-2">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="wrap-input100 validate-input input-group is-invalid">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Name">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="wrap-input100 validate-input input-group mt-5" id="Password-toggle">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="wrap-input100 validate-input input-group mt-5" id="Password-toggle">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                autocomplete="new-password" placeholder="Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="wrap-input100 validate-input input-group mt-5" id="Password-toggle">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                autocomplete="new-password" placeholder="Confirm Password">
                        </div>

                        <div class="text-end pt-4">
                            <p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a>
                            </p>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Register
                            </button>
                        </div>
                        <div class="text-center pt-3">
                            <p class="text-dark mb-0">Not a member?<a href="{{ route('register') }}"
                                    class="text-primary ms-1">Sign UP</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
