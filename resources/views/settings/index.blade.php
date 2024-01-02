@extends('layouts.master')

@section('title', __('Settings'))

@section('content')
<div>
    <form action="{{ route('auth.google') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name" class="form-label">{{ __('GOOGLE_CLIENT_ID') }}<span class="text-danger">*</span></label>

            <input id="client_id" type="text" class="form-control @error('client_id') is-invalid @enderror" name="client_id" value="{{ $creds->client_id??'' }}" autofocus placeholder="Google Client Id">
        </div>

        <div class="form-group">
            <label for="name" class="form-label">{{ __('GOOGLE_CLIENT_SECRET') }}<span class="text-danger">*</span></label>

            <input id="client_secret" type="text" class="form-control @error('client_secret') is-invalid @enderror" name="client_secret" value="{{ $creds->client_secret??'' }}" autofocus placeholder="Google Client Secret">
        </div>

        <div class="form-group">
            <label for="name" class="form-label">{{ __('GOOGLE_REDIRECT_URI') }}<span class="text-danger">*</span> ( Note: {{'base-url'}}/auth/google/callback )</label>

            <input id="redirect_uri" type="text" class="form-control @error('callback_uri') is-invalid @enderror" name="redirect_uri" value="{{ $creds->redirect_uri??'' }}" autofocus placeholder="Google Redirect URI">
        </div>

        <button class="btn btn-primary mt-5">{{ !$creds ? 'Connect with Google' : 'Account Connected' }}</button>

    </form>
</div>

@endsection