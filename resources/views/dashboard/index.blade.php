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
                                        <h2 class="mb-0 number-font" id='totalProduct'>-</h2>
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
                                    <h2 class="mb-0 number-font" id='products'>-</h2>
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
                                    <h2 class="mb-0 number-font" id='draftedData'>-</h2>
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
                                    <h2 class="mb-0 number-font" id='inStock'>-</h2>
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
                                    <h2 class="mb-0 number-font" id='outStock'>-</h2>
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
                                    <h2 class="mb-0 number-font" id='lowStock'>-</h2>
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
                                    <h2 class="mb-0 number-font" id='demandStock'>-</h2>
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

@push('js')
<script>
    $(document).ready(function() {
        getDraftedInventory();

        getInventoryData();

        getInventoryOutStockData();

        getInventoryLowStockData();

        getInventoryDemandStockData();

        function getDraftedInventory() {
            localStorage.setItem('drafted', 0);
            $.ajax({
                type: "POST",
                url: "{{ route('drafted.posts') }}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#draftedData").html(result.length);
                    localStorage.setItem('drafted', result.length);
                    getTotalProducts();
                    setTimeout(() => {
                        getInventoryInStockData();

                    }, 1000);
                },
            });
        }

        function getInventoryData() {
            localStorage.setItem('totalPublished', 0);
            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: ''
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#products").html(result);
                    localStorage.setItem('totalPublished', result);
                },
            });
        }

        function getInventoryOutStockData() {
            localStorage.setItem('outStock', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Stk_o'
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    localStorage.setItem('outStock', result);

                    localStorage.setItem('out_Stock', 0);

                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.posts.count') }}",
                        data: {
                            category: 'stock__out'
                        },
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {
                            localStorage.setItem('out_Stock', result);
                            var getCount = localStorage.getItem('outStock');
                            $("#outStock").html(parseInt(result) + parseInt(getCount));
                        },
                    });
                },
            });
        }

        function getInventoryLowStockData() {
            localStorage.setItem('lowStock', 0);
            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Stk_l'
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // $("#lowStock").html(result);
                    localStorage.setItem('lowStock', result);

                    localStorage.setItem('low_stock', 0);

                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.posts.count') }}",
                        data: {
                            category: 'stock__low'
                        },
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {
                            localStorage.setItem('low_stock', result);
                            var getCount = localStorage.getItem('low_stock');
                            $("#lowStock").html(parseInt(result) + parseInt(getCount));
                        },
                    });
                },
            });
        }

        function getInventoryDemandStockData() {
            localStorage.setItem('demandStock', 0);
            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Stk_d'
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // $("#demandStock").html(result);
                    localStorage.setItem('demandStock', result);

                    localStorage.setItem('out_demand', 0);

                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.posts.count') }}",
                        data: {
                            category: 'stock__demand'
                        },
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {
                            localStorage.setItem('out_demand', result);
                            var getCount = localStorage.getItem('demandStock');
                            $("#demandStock").html(parseInt(result) + parseInt(getCount));
                        },
                    });
                },
            });
        }

        function getTotalProducts() {
            $("#totalProduct").html(parseInt(localStorage.getItem('drafted')) + parseInt(localStorage.getItem('totalPublished')));
        }

        function getInventoryInStockData() {
            var totalProducts = parseInt(localStorage.getItem('totalPublished'));
            var otherData = parseInt(localStorage.getItem('low_stock')) + parseInt(localStorage.getItem('out_Stock')) + parseInt(localStorage.getItem('out_demand')) + parseInt(localStorage.getItem('demandStock')) + parseInt(localStorage.getItem('outStock')) + parseInt(localStorage.getItem('lowStock'));

            $("#inStock").html(totalProducts - otherData);
        }
    })
</script>

@endpush