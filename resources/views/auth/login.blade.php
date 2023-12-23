@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <span class="login100-form-title pb-5">
            Login
        </span>

        <div class="panel panel-primary">
            <div class="panel-body tabs-menu-body p-0 pt-2">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="wrap-input100 validate-input input-group is-invalid">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email" value="{{ old('email') }}" autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="wrap-input100 validate-input input-group mt-5" id="Password-toggle">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Password" autofocus>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="text-end pt-4">
                            <p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a>
                            </p>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Login
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
