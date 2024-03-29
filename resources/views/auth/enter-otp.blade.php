@extends('layouts.app')

@section('title', 'Auth')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <span class="login100-form-title pb-5">
        Enter OTP
    </span>

    <div class="panel panel-primary">
        <div class="panel-body tabs-menu-body p-0 pt-2">
            <div class="tab-content">
                <div class="tab-pane active" id="tab5">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    <input type="hidden" value="{{ request()->email }}" name="email" />
                    <input type="hidden" value="{{ request()->password }}" name="password" />

                    <div class="wrap-input100 validate-input input-group is-invalid">
                        <input type="text" class="form-control" name="otp" placeholder="Enter OTP...." autocomplete="email" autofocus>
                    </div>

                    <div class="row mb-5">
                        <div class="col-6">
                            <p class="mb-0"><a href="{{ route('login') }}" style="cursor: pointer;" class="text-primary ms-1">Back to Login</a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0"><a onclick="location.reload();" style="cursor: pointer;" class="text-primary ms-1">Regenerate OTP</a>
                            </p>
                        </div>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn btn-primary validate">
                            Validate
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.validate').click(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{ route('verify.otp') }}",
                data: {
                    otp: $('#otp').val(),
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(result) {
                    console.log(result);
                },
                error: function(error) {
                    alert(JSON.parse(error.responseText).message);
                }
            });
        });
    })
</script>
@endpush