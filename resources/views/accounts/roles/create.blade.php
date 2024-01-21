@extends('layouts.master')

@section('title', __('Create Role'))

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">{{ __('Create New Roles') }}</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Roles') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Create New Roles') }}</li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->

        <!-- Row -->
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">
                                {{ __('Create New Roles') }}
                            </h4>

                            <button type="submit" class="btn btn-primary float-right">Save</button>
                        </div>

                        <div class="card-body">
                            <div>
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Add New Role') }}</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Permissions') }}</label>

                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-4">
                                                <div class="custom-controls-stacked">
                                                    <label class="custom-control custom-checkbox-md">
                                                        <input type="checkbox" class="custom-control-input" type="checkbox"
                                                            name="permissions[]" id="permission_{{ $permission->id }}"
                                                            value="{{ $permission->name }}">
                                                        <label class="custom-control-label"
                                                            for="permission_{{ $permission->id }}">
                                                            {{ $permission->name }}</label>
                                                    </label>
                                                </div>
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
