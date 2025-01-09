@extends('layouts.master')

@section('title', __('Create User'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Create User') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Users') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Create User') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Create User') }}
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

                            <div class="form-group col-3">
                                <label for="account_type" class="form-label">{{ __('Account Type') }}<span class='text-danger'>*</span></label>
                                <select class="form-control" name="account_type">
                                    <option value="">--Select--</option>
                                    <option value=1>Individual</option>
                                    <option value=2>Retailer</option>
                                    <option value=3>Publisher</option>
                                    <option value=4>Wholesaler</option>
                                </select>

                                @error('account_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="aadhaar_no" class="form-label">{{ __('Aadhaar No.') }}<span class='text-danger'>*</span></label>
                                <input id="aadhaar_no" type="number" class="form-control @error('aadhaar_no') is-invalid @enderror" name="aadhaar_no" value="{{ old('aadhaar_no') }}" autocomplete="aadhaar_no" autofocus placeholder="Aadhaar No">

                                @error('aadhaar_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="father_name" class="form-label">{{ __('Father name') }}<span class='text-danger'>*</span></label>
                                <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name" value="{{ old('father_name') }}" autocomplete="father_name" autofocus placeholder="Father name">

                                @error('father_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="mother_name" class="form-label">{{ __('Mother name') }}<span class='text-danger'>*</span></label>
                                <input id="mother_name" type="text" class="form-control @error('mother_name') is-invalid @enderror" name="mother_name" value="{{ old('mother_name') }}" autocomplete="mother_name" autofocus placeholder="Mother name">

                                @error('mother_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="state" class="form-label">{{ __('State') }}<span class='text-danger'>*</span></label>
                                <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" autocomplete="state" autofocus placeholder="State">

                                @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="pincode" class="form-label">{{ __('Pincode') }}<span class='text-danger'>*</span></label>
                                <input id="pincode" type="number" class="form-control @error('pincode') is-invalid @enderror" name="pincode" value="{{ old('pincode') }}" autocomplete="pincode" autofocus placeholder="Pincode">

                                @error('pincode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="password" class="form-label">{{ __('Password') }}<span class='text-danger'>*</span></label>

                                <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                                    </a>
                                    <input id='password' class="input100 border-start-0 ms-0 form-control" type="password" placeholder="Password" name="password">
                                </div>
                                <input id="plain_password" type="hidden" name="plain_password">

                                <!-- <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Password" > -->

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}<span class='text-danger'>*</span></label>

                                <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                                    </a>

                                    <input id="password-confirm" name="password_confirmation" class="input100 border-start-0 ms-0 form-control" type="password" placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="form-group col-3">
                                <label for="sessions" class="form-label">{{ __('Allow Sessions') }}<span class='text-danger'>*</span></label>

                                <select class="form-control" name="allow_sessions">
                                    <option value="">--Select--</option>
                                    <option value=1>Single Session</option>
                                    <option value=0>Multiple Sessions</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-3">
                                <label for="password" class="form-label">{{ __('Listing Rates (Per Listings)') }}<span class='text-danger'>*</span></label>
                                <input id="price" type="number" class="form-control @error('posting_rate') is-invalid @enderror" name="posting_rate" placeholder="Posting Price" >

                                @error('posting_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-3">
                                <label class="form-label">{{ __('Show Account Health') }}<span class='text-danger'>*</span></label>
                                <select class="form-control" name="show_health">
                                    <option value=1 >Yes - Display Account Rating</option>
                                    <option value=0 >No - For Freshers</option>
                                </select>

                                @error('show_health')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-3">
                                <label class="form-label">{{ __('OTP Features') }}<span class='text-danger'>*</span></label>
                                <select class="form-control" name="otp_feature">
                                    <option value=1 >On - Login Via OTP</option>
                                    <option value=0 >Off - No OTP Required</option>
                                </select>

                                @error('otp_feature')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-3">
                                <label class="form-label">{{ __('Data Transfer') }}<span class='text-danger'>*</span></label>
                                <select class="form-control" name="data_transfer">
                                    <option value=1 >On - Working Mode</option>
                                    <option value=0 >Off - Practice Mode</option>
                                </select>

                                @error('data_transfer')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-3">
                                <label class="form-label">{{ __('Account Details Change Limitations') }}<span class='text-danger'>*</span></label>
                                <select class="form-control" name="account_details_change_limitations">
                                    <option value=1 >On - No Changes Allowed</option>
                                    <option value=0 >Off - User Can Change</option>
                                </select>

                                @error('account_details_change_limitations')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-3"></div>
                            <div class="form-group col-3"></div>

                            <div class="form-group col-6">
                                <label for="name" class="form-label">{{ __('Full Address') }}<span class='text-danger'>*</span></label>
                                <textarea class="form-control" name="full_address" cols=10 rows=5></textarea>

                                @error('full_address')
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
    $(document).ready(function() {
        $("#password").on("change", function() {
            $("#plain_password").val($(this).val());
        })

        $("#password").on("input", function() {
            $("#plain_password").val($(this).val());
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

        $("#Password-toggle1 a").on('click', function(event) {
            event.preventDefault();
            if ($('#Password-toggle1 input').attr("type") == "text") {
                $('#Password-toggle1 input').attr('type', 'password');
                $('#Password-toggle1 i').addClass("zmdi-eye");
                $('#Password-toggle1 i').removeClass("zmdi-eye-off");
            } else if ($('#Password-toggle1 input').attr("type") == "password") {
                $('#Password-toggle1 input').attr('type', 'text');
                $('#Password-toggle1 i').removeClass("zmdi-eye");
                $('#Password-toggle1 i').addClass("zmdi-eye-off");
            }
        });
    })
</script>

@endpush