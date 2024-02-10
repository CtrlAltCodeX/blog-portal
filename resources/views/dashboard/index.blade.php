@extends('layouts.master')

@section('title', __("Dashboard"))

@php
$getStats = app('App\Http\Controllers\DashboardController');
$getRoles = app('App\Http\Controllers\RoleController');

@endphp

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header m-0">
        <h1 class="page-title">{{ __('Products') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Dashboard') }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <a href="{{ route('inventory.index') }}">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total Products</h6>
                                        <h2 class="mb-0 number-font">{{ ($getStats->getStats() + count($allDraftedGooglePosts)) }}</h2>
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
                                    <h6 class="">Total Published</h6>
                                    <h2 class="mb-0 number-font">{{ (($getStats->getStats() )) }}</h2>
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
                                    <h6 class="">Total Draft</h6>
                                    <h2 class="mb-0 number-font">{{ count($allDraftedGooglePosts) }}</h2>
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
                                    <h6 class="">Coming Soon</h6>
                                    <h2 class="mb-0 number-font">Coming Soon</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="page-header m-0">
        <h1 class="page-title">{{ __('Stock') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total In Stock</h6>
                                    <h2 class="mb-0 number-font">{{ $getStats->getStats() - ($getStats->getStats('Stk_o') + $getStats->getStats('Stk_l') + $getStats->getStats('Stk_d') + + $getStats->getStats('stock__low') + + $getStats->getStats('stock__demand') + + $getStats->getStats('stock__out')) }}</h2>
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
                                    <h6 class="">Total Out Stock</h6>
                                    <h2 class="mb-0 number-font">{{ $getStats->getStats('Stk_o') + $getStats->getStats('stock__out') }}</h2>
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
                                    <h6 class="">Total Low Stock</h6>
                                    <h2 class="mb-0 number-font">{{ $getStats->getStats('Stk_l') + $getStats->getStats('stock__low') }}</h2>
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
                                    <h6 class="">Total On Demand</h6>
                                    <h2 class="mb-0 number-font">{{ $getStats->getStats('Stk_d') + $getStats->getStats('stock__demand') }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header m-0">
        <h1 class="page-title">{{ __('Users') }}</h1>
    </div>

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
</div>
@endsection