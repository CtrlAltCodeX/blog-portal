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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Joan Powell</td>
                                        <td>Associate Developer</td>
                                        <td>$450,870</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Gavin Gibson</td>
                                        <td>Account manager</td>
                                        <td>$230,540</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Julian Kerr</td>
                                        <td>Senior Javascript Developer</td>
                                        <td>$55,300</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Cedric Kelly</td>
                                        <td>Accountant</td>
                                        <td>$234,100</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Samantha May</td>
                                        <td>Junior Technical Author</td>
                                        <td>$43,198</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
