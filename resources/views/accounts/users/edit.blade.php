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
    <form action="{{ route('users.update', $user->id) }}" method="POST">
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
                                    <label for="account_type" class="form-label">{{ __('Account Type') }}</label>
                                    <select class="form-control" name="account_type">
                                        <option value="">--Select--</option>
                                        <option value=1 {{ $user->account_type == 1 ? 'selected' : '' }}>Individual</option>
                                        <option value=2 {{ $user->account_type == 2 ? 'selected' : '' }}>Retailer</option>
                                        <option value=3 {{ $user->account_type == 3 ? 'selected' : '' }}>Publisher</option>
                                        <option value=4 {{ $user->account_type == 4 ? 'selected' : '' }}>Wholesaler</option>
                                    </select>

                                    @error('account_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="aadhaar_no" class="form-label">{{ __('Aadhaar No.') }}</label>
                                    <input id="aadhaar_no" type="number" class="form-control @error('aadhaar_no') is-invalid @enderror" name="aadhaar_no" value="{{ old('aadhaar_no') ?? $user->aadhaar_no }}" autocomplete="aadhaar_no" autofocus placeholder="Aadhaar No">

                                    @error('aadhaar_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="father_name" class="form-label">{{ __('Father name') }}</label>
                                    <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name" value="{{ old('father_name') ?? $user->father_name }}" autocomplete="father_name" autofocus placeholder="Father name">

                                    @error('father_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="mother_name" class="form-label">{{ __('Mother name') }}</label>
                                    <input id="mother_name" type="text" class="form-control @error('mother_name') is-invalid @enderror" name="mother_name" value="{{ old('mother_name') ?? $user->mother_name }}" autocomplete="mother_name" autofocus placeholder="Mother name">

                                    @error('mother_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="state" class="form-label">{{ __('State') }}</label>
                                    <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') ?? $user->state}}" autocomplete="state" autofocus placeholder="State">

                                    @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="pincode" class="form-label">{{ __('Pincode') }}</label>
                                    <input id="pincode" type="number" class="form-control @error('pincode') is-invalid @enderror" name="pincode" value="{{ old('pincode') ?? $user->pincode }}" autocomplete="pincode" autofocus placeholder="Pincode">

                                    @error('pincode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>

                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>

                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">
                                </div>

                                <div class="form-group col-3">
                                    <label for="status" class="form-label">{{ __('Status') }}</label>

                                    <select id="status" class="form-control @error('status') is-invalid @enderror" name="status">
                                        <option value=0 {{ $user->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        <option value=1 {{ $user->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    </select>

                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-6">
                                    <label for="name" class="form-label">{{ __('Full Address') }}</label>
                                    <textarea class="form-control" name="full_address" cols=10 rows=5>
                                    {{ $user->full_address }}
                                    </textarea>

                                    @error('full_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="roles" class="form-label">{{ __('Roles') }}</label>

                                    @foreach ($roles as $role)
                                    <div class="custom-controls-stacked">
                                        <label class="custom-control custom-checkbox-md">
                                            <input type="checkbox" class="custom-control-input" type="checkbox" name="roles[]" id="role_{{ $role->id }}" @if (count($user->roles->where('id', $role->id))) checked @endif
                                            value="{{ $role->name }}">
                                            <label class="custom-control-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}</label>
                                        </label>
                                    </div>
                                    @endforeach
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