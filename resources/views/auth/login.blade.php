@extends('layouts.app')

@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('verify.otp') }}" id='form'>
    @csrf

    <span class="login100-form-title pb-5">
        Login
    </span>

    <div class="panel panel-primary">
        <div class="panel-body tabs-menu-body p-0 pt-2">
            <div class="tab-content">
                <div class="tab-pane active" id="tab5">
                    @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {!! session('error') !!}
                    </div>
                    @endif
                    <div class="wrap-input100 validate-input input-group is-invalid">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" @if(session()->get('session_email')) value="{{ session()->get('session_email') }}" @else value="{{ old('email') }}" @endif autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="wrap-input100 validate-input input-group mt-5" id="Password-toggle">
                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                            </a>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" autofocus @if(session()->get('session_password')) value="{{ session()->get('session_password') }}" @endif>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                    <div class="text-end pt-4">
                        <p class="mb-0"><a href="{{ route('password.request') }}" class="text-primary ms-1">Forgot Password?</a>
                        </p>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn btn-primary">
                            Login
                        </button>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type='button' class="login100-form-btn btn-primary" id='without-otp'>
                            Login without OTP
                        </button>
                    </div>
                    <!-- <div class="text-center pt-3">
                        <p class="text-dark mb-0">Not a member?<a href="{{ route('register') }}" class="text-primary ms-1">Sign UP</a></p>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
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
                success: function(result) {},
            });

            $('#form').attr('action', "{{ route('verify.otp') }}");
            $('#form').submit();
        });
    })
</script>

@endpush