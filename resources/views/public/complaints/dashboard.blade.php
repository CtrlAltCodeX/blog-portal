@extends('layouts.public_complaint')

@push('css')
<style>
    .dashboard-card {
      border: none;
      border-radius: 18px !important;
      transition: all 0.3s ease;
      background: #fff;
      padding: 2.5rem 2rem;
      text-align: center;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .dashboard-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .icon-circle {
      width: 75px;
      height: 75px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      margin: 0 auto 1.5rem auto;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .bg-complaint {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }
</style>
@endpush

@section('content')
<section class="bg-complaint">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center text-white">
            <span class="text-uppercase small tracking-wider opacity-75">Complaint Management System</span>
            <h1 class="fw-bold mt-2 mb-3">What would you like to do?</h1>
            <p class="lead opacity-90 mb-4">Quickly create, reply or track your complaints online.</p>
            <div>
                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem;">
                    <i class="fas fa-user-check text-success me-1"></i> 
                    Verified: {{ \App\Models\ComplaintUser::find(session('complaint_user_id'))->email }}
                </span>
            </div>
        </div>
      </div>
      
      <div class="row g-4 justify-content-center">
        <!-- Create -->
        <div class="col-lg-4 col-md-6">
          <div class="dashboard-card shadow">
            <div>
                <div class="icon-circle bg-primary text-white" style="background: linear-gradient(45deg, #00C6FF, #0072FF) !important;">
                  <i class="fas fa-plus"></i>
                </div>
                <h4 class="fw-bold mb-2">Create Complaint</h4>
                <p class="text-muted mb-4">Raise a new issue and detail your order problems.</p>
            </div>
            <a href="{{ route('public.complaints.create') }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm" style="background: linear-gradient(135deg, #00C6FF, #0072FF); border: none;">
              CREATE NEW <i class="fas fa-chevron-right ms-2 small"></i>
            </a>
          </div>
        </div>

        <!-- Reply -->
        <div class="col-lg-4 col-md-6">
          <div class="dashboard-card shadow">
            <div>
                <div class="icon-circle bg-success text-white" style="background: linear-gradient(45deg, #11998e, #38ef7d) !important;">
                  <i class="fas fa-reply"></i>
                </div>
                <h4 class="fw-bold mb-2">Reply to Complaint</h4>
                <p class="text-muted mb-4">Respond to updates from our support desk.</p>
            </div>
            <a href="{{ route('public.complaints.index') }}" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm" style="background: linear-gradient(135deg, #11998e, #38ef7d); border: none;">
              REPLY NOW <i class="fas fa-chevron-right ms-2 small"></i>
            </a>
          </div>
        </div>

        <!-- Status -->
        <div class="col-lg-4 col-md-6">
          <div class="dashboard-card shadow">
            <div>
                <div class="icon-circle bg-warning text-white" style="background: linear-gradient(45deg, #f12711, #f5af19) !important;">
                  <i class="fas fa-search"></i>
                </div>
                <h4 class="fw-bold mb-2">Check Status</h4>
                <p class="text-muted mb-4">Track your complaint progress and solutions.</p>
            </div>
            <a href="{{ route('public.complaints.index') }}" class="btn btn-warning w-100 rounded-pill py-2 fw-bold shadow-sm text-white" style="background: linear-gradient(135deg, #f12711, #f5af19); border: none;">
              CHECK STATUS <i class="fas fa-chevron-right ms-2 small"></i>
            </a>

          </div>
        </div>
      </div>
      
      <div class="text-center mt-5">
          <a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> Back to Home</a>
      </div>
    </div>
</section>
@endsection
