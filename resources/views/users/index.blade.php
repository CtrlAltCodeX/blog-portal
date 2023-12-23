@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Users</h1>
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
                            Users
                        </h4>

                        <a href="{{ route('users.create') }}" class="btn btn-primary float-right">Create User</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="{{ __('Actions') }}">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">{{ __('EDIT') }}</a>
                                                    <button type="button" onclick="return confirm('{{ __('Are you sure you want to delete this record?') }}') ? document.getElementById('delete-user').submit() : false;" class="btn btn-danger">{{ __('DELETE') }}</button>

                                                    <form action="{{ route('users.destroy', $user->id) }}" id="delete-user" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">{{ __('No records found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if (count($users))
                        <div class="card-footer">
                            {!! $users->links() !!}
                        </div>                       
                    @endif
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
