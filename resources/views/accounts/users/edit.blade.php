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
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') ?? $user->name }}" autocomplete="name" autofocus
                                        placeholder="Name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>

                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') ?? $user->email }}" autocomplete="email"
                                        placeholder="Email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">{{ __('Roles') }}</label>

                                    <div class="row">
                                        @foreach ($roles as $role)
                                            <div class="col-md-4">
                                                <div class="custom-controls-stacked">
                                                    <label class="custom-control custom-checkbox-md">
                                                        <input type="checkbox" class="custom-control-input" type="checkbox"
                                                            name="roles[]" id="role_{{ $role->id }}"
                                                            @if (count($user->roles->where('id', $role->id))) checked @endif
                                                            value="{{ $role->name }}">
                                                        <label class="custom-control-label" for="role_{{ $role->id }}">
                                                            {{ $role->name }}</label>
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
