@extends('layouts.master')

@section('title', __('Backup'))

@section('content')
<div class="card mt-5">
    <div class="card-body">
        <div>
            <form action="{{ route('settings.emails.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Emails') }}<span class="text-danger">*</span></label>

                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="emails[]" value="{{ $siteSettings->url??"" }}" autofocus placeholder="Emails">
                </div>

                <button class="btn btn-primary mt-5">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection