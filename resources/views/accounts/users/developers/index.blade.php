@extends('layouts.master')

@section('title', 'Users')

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Developers List</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Developers List</a></li>
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
                        Developers List
                    </h4>
                    <form action='{{ route("users.index") }}' method='GET' id='countform'>
                        <input type='hidden' name='page' value='{{ request()->page }}' />
                        <select class='form-control' name='users' id='count'>
                            <option value='50' {{ request()->users == 50 ? 'selected' : '' }}>50</option>
                            <option value='100' {{ request()->users == 100 ? 'selected' : '' }}>100</option>
                            <option value='150' {{ request()->users == 150 ? 'selected' : '' }}>150</option>
                        </select>
                    </form>

                    {{-- @can('User create') --}}
                        <a href="{{ route('developers.create') }}"
                            class="btn btn-primary float-right">{{ __('Create Developer') }}</a>
                    {{-- @endcan --}}
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
                                    <th>{{ __('API KEY') }}</th>
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
                                    <td>{{ $user->api_key }}</td>
                                    <td>
                                        @php
                                            $statusLabels = [
                                                0 => ['label' => 'Inactive', 'color' => 'danger'],
                                                1 => ['label' => 'Active', 'color' => 'success'],
                                                2 => ['label' => 'Suspended', 'color' => 'warning'],
                                                3 => ['label' => 'Blocked', 'color' => 'dark']
                                            ];
                                        @endphp

                                        <span class="badge bg-{{ $statusLabels[$user->api_key == null ? 0 : 1]['color'] }}">
                                            {{ $statusLabels[$user->api_key == null ? 0 : 1]['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="{{ __('Actions') }}">
                                            <a href="{{ route('developers.api-key-gen', $user->id) }}" class="btn btn-success">{{ __('API KEY REFRESH') }}</a>
                                            @can('User Details -> All Users List -> Edit')
                                            <a href="{{ route('developers.edit', $user->id) }}" class="btn btn-primary">{{ __('EDIT') }}</a>
                                            @endcan

                                            @can('User delete')
                                            <button type="button" onclick="return confirm('{{ __('Are you sure you want to delete this record?') }}') ? document.getElementById('delete-user{{$user->id}}').submit() : false;" class="btn btn-danger">{{ __('DELETE') }}</button>

                                            <form action="{{ route('developers.destroy', $user->id) }}" id="delete-user{{$user->id}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No records found.') }}</td>
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


