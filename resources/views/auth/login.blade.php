@extends('layouts.app')

@section('title', 'Login')

@php
$siteSettings = app('App\Models\SiteSetting')->first();
$buttonOne = explode(',',$siteSettings?->button_1);
$buttonTwo = explode(',',$siteSettings?->button_2);
$buttonThree = explode(',',$siteSettings?->button_3);
$buttonFour = explode(',',$siteSettings?->button_4);
@endphp

@section('content')
<form method="POST" action="{{ route('login') }}" id='form'>
    @csrf

    <span class="login100-form-title pb-5">
        Login
    </span>

    <div class="panel panel-primary">
        <div class="panel-body tabs-menu-body p-0 pt-2">
            <div class="tab-content">
                <div class="tab-pane active" id="tab5">
                    @if(session('success'))
                    <div class="alert alert-primary" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if(session('expire_error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('expire_error') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {!! session('error') !!}
                    </div>
                    @endif
                    <label>Email / Username</label>
                    <div class="wrap-input100 validate-input input-group is-invalid">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" @if(session()->get('session_email')) value="{{ session()->get('session_email') }}" @else value="{{ old('email') }}" @endif autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <label class="mt-2">Password</label>
                    <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" autofocus @if(session()->get('session_password')) value="{{ session()->get('session_password') }}" @endif>
                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                            </a>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                    <div class="text-end pt-4 d-flex justify-content-between">
                        <p class="mb-0"><a href="{{ route('register') }}" class="text-primary ms-1">Sign Up (Management)</a>
                        </p>
                        <p class="mb-0"><a href="{{ route('password.request') }}" class="text-primary ms-1">Forgot Password?</a>
                        </p>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn btn-primary">
                            Login To Get OTP
                        </button>
                    </div>
                    <div class="d-grid mt-3 justify-content-center" style="grid-gap: 10px;grid-template-columns: auto auto;">
                        @if($buttonOne[0])
                        <a href="{{ $buttonOne[1] }}" target='_blank'>{{$buttonOne[0]}}</a>
                        @endif
                        @if($buttonTwo[0])
                        <a href="{{ $buttonTwo[1] }}" target='_blank'>{{$buttonTwo[0]}}</a>
                        @endif
                        @if($buttonThree[0])
                        <a href="{{ $buttonThree[1] }}" target='_blank'>{{$buttonThree[0]}}</a>
                        @endif
                        @if($buttonFour[0])
                        <a href="{{ $buttonFour[1] }}" target='_blank'>{{$buttonFour[0]}}</a>
                        @endif
                    </div>
                    <!-- <div class="text-center pt-3">
                        <p class="text-dark mb-0">Not a member?<a href="{{ route('register') }}" class="text-primary ms-1">Sign UP</a></p>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</form>
@if(session('blocked'))
<div id="modal" style="display: flex; position: fixed; inset: 0; align-items: center; justify-content: center; z-index: 50; background-color: rgba(0, 0, 0, 0.5);">
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 40%; margin: auto; padding: 24px; position: relative; text-align: center;">
        <img src='/blocked-icon.jpg' style="width: 180px;" />
        <h2 id="modalTitle" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 16px;">
            Your account is {!! session('blocked') !!}
        </h2>
        <p style="font-size: 1rem;color: red;margin-bottom: 20px;font-size: 20px;font-weight: 700;">
            Your account has been {!! session('blocked') !!}. Contact support to reactivate your account .
        </p>
        <button id="infoButton" style="background-color: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem;">
            OPEN SUPPORT
        </button>
        <button id="closeModal" style="background: none; border: none; cursor: pointer; color: #6B7280; position: absolute; top: 10px; right: 10px; font-size: 1.2rem;">
            X
        </button>
    </div>
</div>

@endif

@endsection

@push('js')


<script>
    document.getElementById('infoButton').addEventListener('click', function() {
        window.location.href = 'https://support.exam360.in/'; 
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
    });
</script>

<script>
    $(document).ready(function() {
        $('#without-otp').click(function(e) {
            e.preventDefault();

            $('#form').attr('action', "{{ route('login') }}");
            $('#form').submit();
        })

        $("#Password-toggle a").on('click', function(event) {
            event.preventDefault();
            if ($('#Password-toggle input').attr("type") == "text") {
                $('#Password-toggle input').attr('type', 'password');
                $('#Password-toggle i').addClass("zmdi-eye");
                $('#Password-toggle i').removeClass("zmdi-eye-off");
            } else if ($('#Password-toggle input').attr("type") == "password") {
                $('#Password-toggle input').attr('type', 'text');
                $('#Password-toggle i').removeClass("zmdi-eye");
                $('#Password-toggle i').addClass("zmdi-eye-off");
            }
        });

        $('#continue').click(function(e) {
            e.preventDefault();

            $.ajax({
                type: "GET",
                url: "{{ route('user.session.delete', session()->get('session_id')??'') }}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if (result.status) {
                        $('#form').attr('action', "{{ route('verify.otp') }}");
                        $('#form').submit();
                    }
                },
            });
        });

        setTimeout(() => {
            location.reload();
        }, 300000);
    })
</script>

@endpush