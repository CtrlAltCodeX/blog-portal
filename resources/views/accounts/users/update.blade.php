@extends('layouts.master')

@section('title', __('Update User'))

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
    <form action="{{ route('update.users.status') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update User') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="status" class="form-label">{{ __('Status') }}</label>

                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value=0>{{ __('Inactive') }}</option>
                                    <option value=1>{{ __('Active') }}</option>
                                </select>

                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-12">
                                <label for="email" class="form-label">{{ __('Roles') }}</label>

                                @foreach ($roles as $role)
                                <div class="custom-controls-stacked">
                                    <label class="custom-control custom-checkbox-md">
                                        <input type="checkbox" class="custom-control-input" type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->name }}">
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
    </form>
    <!-- End Row -->
</div>
@endsection