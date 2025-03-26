@can('Settings -> Site Access')
@extends('layouts.master')

@section('title', __('Settings'))

@section('content')
<div class="card mt-5">
    <div class="card-body">
        <div>
            <form action="{{ route('settings.policies.save') }}" method="POST" >
                @csrf

                <div class="form-group">
                    <label for="description" class="form-label d-flex justify-content-between">
                        <div>{{ __('Term and Condition') }}</div>
                    </label>

                    <textarea type="text" class="form-control @error('term_and_condition') is-invalid @enderror editor" name="term_and_condition" autocomplete="term_and_condition" autofocus placeholder="Term and Condition" rows="10" id='term_and_condition'>{{ old('term_and_condition')??$siteSettings->term_and_condition }}</textarea>
                    <span class="error-message desc" style="color:red;"></span>

                    @error('term_and_condition')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label d-flex justify-content-between">
                        <div>{{ __('Policies') }}</div>
                    </label>

                    <textarea type="text" class="form-control @error('policy') is-invalid @enderror editor" name="policy" autocomplete="policy" autofocus placeholder="Term and Condition" rows="10" id='policy'>{{ old('policy')??$siteSettings->policy }}</textarea>
                    <span class="error-message desc" style="color:red;"></span>

                    @error('policy')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-primary mt-5" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $('.editor').summernote({
        toolbar: [
            ['font', ['bold', 'italic', 'underline']],
            ['para', ['paragraph']],
        ],
    });
</script>
@endpush
@endcan