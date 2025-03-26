@can('User Details -> All Users List')
@extends('layouts.master')

@section('title', 'Users')

@php
$getRoles = app('App\Http\Controllers\RoleController');
@endphp

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">All Users List</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">All Users List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <a href="{{ route('users.index') }}">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total Users</h6>
                                        <h2 class="mb-0 number-font">{{ $allUser }}</h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <a href="{{ route('verified.users') }}">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total Active User</h6>
                                        <h2 class="mb-0 number-font">{{ $active }}</h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total InActive User</h6>
                                    <h2 class="mb-0 number-font">{{ $inactive }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total Roles</h6>
                                    <h2 class="mb-0 number-font">{{ $getRoles->rolesCount() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        All Users List
                    </h4>
                    <form action='{{ route("users.index") }}' method='GET' id='countform'>
                        <input type='hidden' name='page' value='{{ request()->page }}' />
                        <select class='form-control' name='users' id='count'>
                            <option value='50' {{ request()->users == 50 ? 'selected' : '' }}>50</option>
                            <option value='100' {{ request()->users == 100 ? 'selected' : '' }}>100</option>
                            <option value='150' {{ request()->users == 150 ? 'selected' : '' }}>150</option>
                        </select>
                    </form>

                    <!-- @can('User create')
                            <a href="{{ route('users.create') }}"
                                class="btn btn-primary float-right">{{ __('Create User') }}</a>
                        @endcan -->
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap text-md-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('System ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Mobile') }}</th>
                                    <th>{{ __('Roles') }}</th>
                                    <th>{{ __('Listing Rate') }}</th>
                                    <th>{{ __('Account Health') }}</th>
                                    <th>{{ __('OTP Features') }}</th>
                                    <th>{{ __('Data Transfer') }}</th>
                                    <th>{{ __('A/C Change Limit') }}</th>
                                    <th>{{ __('Session Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    @canany(['User Details -> All Users List -> Edit', 'User delete'])
                                    <th>{{ __('Actions') }}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @can('User Details (Main Menu)')
                                @forelse ($users as $key => $user)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>
                                        @foreach ($user->roles as $index => $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                        @if (($index + 1) % 5 == 0)
                                        <br>
                                        @endif
                                        @endforeach
                                    </td>
                                    </td>
                                    <td>₹ {{ $user->posting_rate }}</td>
                                    <td>{{ $user->show_health ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user->otp_feature ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user->data_transfer ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user->account_details_change_limitations ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user->allow_sessions ? 'Single' : 'Multiple' }}</td>
                                    <td>
                                        @php
                                            $statusLabels = [
                                                0 => ['label' => 'Inactive', 'color' => 'danger'],
                                                1 => ['label' => 'Active', 'color' => 'success'],
                                                2 => ['label' => 'Suspended', 'color' => 'warning'],
                                                3 => ['label' => 'Blocked', 'color' => 'dark']
                                            ];
                                        @endphp

                                        <span class="badge bg-{{ $statusLabels[$user->status]['color'] }}">
                                            {{ $statusLabels[$user->status]['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="{{ __('Actions') }}">
                                            @can('User Details -> All Users List -> Edit')
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">{{ __('EDIT') }}</a>
                                            @endcan

                                            @can('User delete')
                                            <button type="button" onclick="return confirm('{{ __('Are you sure you want to delete this record?') }}') ? document.getElementById('delete-user{{$user->id}}').submit() : false;" class="btn btn-danger">{{ __('DELETE') }}</button>

                                            <form action="{{ route('users.destroy', $user->id) }}" id="delete-user{{$user->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('No records found.') }}</td>
                                </tr>
                                @endforelse
                                @endcan
                            </tbody>
                        </table>
                    </div>
                </div>

                @can('User Details (Main Menu)')
                @if (count($users))
                <div class="card-footer">
                    {!! $users->links() !!}
                </div>
                @endif
                @endcan
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>

@endsection

@push('js')
<script>
    $(document).ready(function(){
         $("#count").on('change', function(){
             $("#countform").submit();
         })
    });
</script>
@endpush
@endcan


