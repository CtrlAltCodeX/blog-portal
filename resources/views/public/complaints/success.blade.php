@extends('layouts.public_complaint')

@section('content')
<div class="main-content d-flex align-items-center justify-content-center" style="background: #f8f9fa; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="card shadow-lg border-0 py-5 px-4" style="border-radius: 30px;">
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="display-1 text-success mb-4">
                                <i class="fas fa-check-circle animate__animated animate__bounceIn"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-2">Complaint Submitted!</h2>
                            <p class="text-muted mb-5">Your complaint has been successfully registered in our system. Our team will review it shortly.</p>
                        </div>

                        <div class="bg-light rounded-4 p-4 mb-5 border">
                            <p class="small text-muted text-uppercase fw-bold mb-2">Your Ticket ID</p>
                            <h3 class="fw-bold text-primary mb-0" style="letter-spacing: 2px;">{{ $ticket_id }}</h3>
                        </div>

                        <div class="d-grid gap-3">
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-lg" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                <i class="fas fa-home me-2"></i> BACK TO HOME PAGE
                            </a>
                            <p class="small text-muted mt-3">Please keep your Ticket ID for future reference.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .animate__bounceIn {
        animation-duration: 1.2s;
    }
</style>
@endpush
@endsection
