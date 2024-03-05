@extends('layouts.master')

@section('title', __('Settings'))

@section('content')
@can('Configure Blog Update')
<div class="card mt-5">
    <div class="card-body">
        <div>
            <form action="{{ route('auth.google') }}" method="POST" id='authForm'>
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('GOOGLE_CLIENT_ID') }}<span class="text-danger">*</span></label>

                    <input id="client_id" type="text" class="form-control @error('client_id') is-invalid @enderror" name="client_id" {{ isset($bloggerCreds->client_id) ? 'readonly' : '' }} value="{{ $bloggerCreds->client_id??'' }}" autofocus placeholder="Google Client Id">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('GOOGLE_CLIENT_SECRET') }}<span class="text-danger">*</span></label>

                    <input id="client_secret" type="text" class="form-control @error('client_secret') is-invalid @enderror" name="client_secret" {{ isset($bloggerCreds->client_secret) ? 'readonly' : '' }} value="{{ $bloggerCreds->client_secret??'' }}" autofocus placeholder="Google Client Secret">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">{{ __('GOOGLE_REDIRECT_URI') }}<span class="text-danger">*</span> ( Note: {{'base-url'}}/auth/google/callback )</label>

                    <input id="redirect_uri" type="text" class="form-control @error('callback_uri') is-invalid @enderror" name="redirect_uri" {{ isset($bloggerCreds->redirect_uri) ? 'readonly' : '' }} value="{{ $bloggerCreds->redirect_uri??'' }}" autofocus placeholder="Google Redirect URI">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Blog Id<span class="text-danger">*</span></label>

                    <input id="blog_id" type="text" class="form-control @error('blog_id') is-invalid @enderror" name="blog_id" {{ isset($bloggerCreds->blog_id) ? 'readonly' : '' }} value="{{ $bloggerCreds->blog_id??'' }}" autofocus placeholder="Blog Id">
                </div>

                <input type="hidden" name="scope" value="Blogger" id='scope' />

                @can('Configure Blog Update')
                <button type='button' class="btn btn-primary mt-5 connect" id='Blogger'>{{ !$bloggerCreds ? 'Connect with Blogger' : 'Account Disconnect' }}</button>
                <button type='button' class="btn btn-primary mt-5 connect" id='Merchant'>{{ !$merchantCreds ? 'Connect with Google Merchant Center' : 'Account Disconnect' }}</button>

                <button class="btn btn-primary mt-5" id="refresh_token">Refresh Token</button>
                @endcan
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

@push("js")
<script>
    $(document).ready(function() {
        $('#refresh_token').click(function(e) {
            e.preventDefault();
            var clientId = $('#client_id').val();
            var client_secret = $('#client_secret').val();
            var redirect_uri = $('#redirect_uri').val();
            var creds = [clientId, client_secret, redirect_uri];

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("google.refresh.token") }}', // Laravel route URL
                type: 'POST',
                dataType: 'json',
                data: {
                    "client_id": clientId,
                    "client_secret": client_secret,
                    'redirect_uri': redirect_uri,
                    "scope": 'Blogger'
                },

                success: function(data) {
                    // window.location.href = data;
                },
                error: function(error) {
                    // Handle errors
                    console.error('AJAX request failed', error);
                }
            });
        })

        $('.connect').click(function() {
            if ($(this).attr('id') == 'Merchant') {
                $("#scope").val($(this).attr('id'));
            }

            $("#authForm").submit();
        })
    })
</script>
@endpush