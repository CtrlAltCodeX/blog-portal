@extends('layouts.master')

@section('title', 'Users')

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Users List Status</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        Users List Status
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
                        <table class="table text-nowrap text-md-nowrap mb-0" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('System ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Mobile') }}</th>
                                    <th>{{ __('Roles') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
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
                                    </td>
                                    <td class="btn-group-sm">
                                        <a href="{{ route('edit.users.status', $user->id) }}" class="btn btn-primary">{{ __('EDIT') }}</a>
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

