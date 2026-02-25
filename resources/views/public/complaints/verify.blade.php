@extends('layouts.public_complaint')

@section('content')
<section class="pad-tb bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Verify Your Identity</h3>
                            <p class="text-muted small">Enter your registered email to receive an OTP</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- @if(session('test_otp'))
                            <div class="alert alert-info">Test OTP: <strong>{{ session('test_otp') }}</strong></div>
                        @endif -->

                        @if(!session('email_sent'))
                            <form action="{{ route('public.complaints.sendOtp') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="example@mail.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                    SEND OTP <i class="fas fa-paper-plane ms-2 small"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('public.complaints.verifyOtp') }}" method="POST">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('email') }}">
                                <div class="mb-4">
                                    <label class="form-label">Enter 6-Digit OTP</label>
                                    <input type="text" name="otp" class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" maxlength="6" placeholder="000000" style="letter-spacing: 12px; font-weight: bold; font-size: 24px;" required>
                                    @error('otp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success btn-lg w-100" style="border-radius: 12px; background: linear-gradient(135deg, #11998e, #38ef7d); border: none;">
                                    VERIFY & PROCEED <i class="fas fa-check-circle ms-2 small"></i>
                                </button>
                                <div class="text-center mt-3">
                                    <button type="submit" form="resend-form" class="btn btn-link text-decoration-none small text-muted">Didn't receive code? Resend</button>
                                </div>
                            </form>
                            <form id="resend-form" action="{{ route('public.complaints.sendOtp') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('email') }}">
                            </form>
                        @endif

                        <div class="text-center mt-4 pt-3 border-top">
                            <a href="{{ url('/') }}" class="text-muted small text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
