@extends('layouts.master')

@section('title', __('Update Role'))

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">{{ __('Update Role') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Roles') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Update Role') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">
                            {{ __('Update Role') }}
                        </h4>

                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>

                    <div class="card-body">
                        <div>
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('Role Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $role->name }}" autocomplete="name" autofocus placeholder="Name">

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <!-- <label for="name" class="form-label">{{ __('Permissions') }}</label> -->

                                <div class="row">
                                    <label for="name" class="form-label">{{ __('Dashboard') }}</label>
                                    @foreach ($permissionsInCategory['Dashboard'] as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-checkbox-md">
                                                <input type="checkbox" class="custom-control-input" type="checkbox" name="permissions[]" id="permission_{{ $permission['id'] }}" @if (count($role->permissions->where('id', $permission['id']))) checked @endif
                                                value="{{ $permission['name'] }}">
                                                <label class="custom-control-label" for="permission_{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach

                                    <label for="name" class="form-label">{{ __('Listing') }}</label>
                                    @foreach ($permissionsInCategory['Listing'] as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-checkbox-md">
                                                <input type="checkbox" class="custom-control-input" type="checkbox" name="permissions[]" id="permission_{{ $permission['id'] }}" @if (count($role->permissions->where('id', $permission['id']))) checked @endif
                                                value="{{ $permission['name'] }}">
                                                <label class="custom-control-label" for="permission_{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach

                                    <label for="name" class="form-label">{{ __('Listing') }}</label>
                                    @foreach ($permissionsInCategory['Inventory'] as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-checkbox-md">
                                                <input type="checkbox" class="custom-control-input" type="checkbox" name="permissions[]" id="permission_{{ $permission['id'] }}" @if (count($role->permissions->where('id', $permission['id']))) checked @endif
                                                value="{{ $permission['name'] }}">
                                                <label class="custom-control-label" for="permission_{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach

                                    <label for="name" class="form-label">{{ __('User') }}</label>
                                    @foreach ($permissionsInCategory['User'] as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-checkbox-md">
                                                <input type="checkbox" class="custom-control-input" type="checkbox" name="permissions[]" id="permission_{{ $permission['id'] }}" @if (count($role->permissions->where('id', $permission['id']))) checked @endif
                                                value="{{ $permission['name'] }}">
                                                <label class="custom-control-label" for="permission_{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach

                                    <label for="name" class="form-label">{{ __('Role & Permissions') }}</label>
                                    @foreach ($permissionsInCategory['Role'] as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-checkbox-md">
                                                <input type="checkbox" class="custom-control-input" type="checkbox" name="permissions[]" id="permission_{{ $permission['id'] }}" @if (count($role->permissions->where('id', $permission['id']))) checked @endif
                                                value="{{ $permission['name'] }}">
                                                <label class="custom-control-label" for="permission_{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}</label>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach

                                    <label for="name" class="form-label">{{ __('Settings') }}</label>
                                    @foreach ($permissionsInCategory['Setting'] as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-checkbox-md">
                                                <input type="checkbox" class="custom-control-input" type="checkbox" name="permissions[]" id="permission_{{ $permission['id'] }}" @if (count($role->permissions->where('id', $permission['id']))) checked @endif
                                                value="{{ $permission['name'] }}">
                                                <label class="custom-control-label" for="permission_{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}</label>
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