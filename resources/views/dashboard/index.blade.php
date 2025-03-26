@extends('layouts.master')

@section('title', __("Dashboard"))

@section('content')

<!-- CONTAINER -->
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header m-0">
        <div class="page-header m-0">
            <h1 class="page-title">{{ __('Your Sessions') }}</h1>
        </div>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Dashboard') }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                        <thead>
                            <tr>
                                <th>{{ __('Sl') }}</th>
                                <th>{{ __('Session Id') }}</th>
                                <th>{{ __('Session Start') }}</th>
                                <th>{{ __('Session Expire') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @foreach($userSessionsCount as $key => $session)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $session->session_id }}</td>
                            <td>{{date("d-m-Y h:i A", strtotime($session->created_at)) }}</td>
                            <td>{{date("d-m-Y h:i A", strtotime($session->expire_at)) }}</td>
                            @if(session()->get('sessionId') != $session->session_id)
                            <td>
                                <a href="{{ route('user.session.delete', $session->session_id) }}" class="btn btn-primary btn-sm">Delete</a>
                            </td>
                            @else
                            <td>
                                <a href="#" class="btn btn-success btn-sm">Current Session</a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header m-0">
        <h1 class="page-title">{{ __('Review Listings ( Main Site )') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">6 Months Old</h6>
                                    <a target="_blank" href="@if(auth()->user()->hasRole('Super Admin') && auth()->user()->hasRole('Super Management')) {{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => 6]) }} @endif">
                                        <h2 class="mb-0 number-font text-success" id='six-month-old'>-</h2>
                                    </a>
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
                                    <h6 class="">1 Year Old</h6>
                                    <a target="_blank" href="@if(auth()->user()->hasRole('Super Admin') && auth()->user()->hasRole('Super Management')) {{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => 1]) }} @endif">
                                        <h2 class="mb-0 number-font" id='one-year-old'>-</h2>
                                    </a>
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
                                    <h6 class="">2 Year Old</h6>
                                    <a target="_blank" href="@if(auth()->user()->hasRole('Super Admin') && auth()->user()->hasRole('Super Management')) {{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => 2]) }} @endif">
                                        <h2 class="mb-0 number-font text-warning" id='two-year-old'>-</h2>
                                    </a>
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
                                    <h6 class="">3 Year Old</h6>
                                    <a target="_blank" href="@if(auth()->user()->hasRole('Super Admin') && auth()->user()->hasRole('Super Management')) {{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => '3Y']) }} @endif">
                                        <h2 class="mb-0 number-font text-danger" id='three-year-old'>-</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header m-0">
        <h1 class="page-title">{{ __('User Listing Counts Report') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mt-2">
                            <h6 class="">Listing Pending</h6>
                            <a target="_blank" href="@if(auth()->user()->hasRole('Super Admin') && auth()->user()->hasRole('Super Management')) {{ route('database-listing.index', ['status' => 0, 'startIndex' => 1, 'user' => 'all']) }} @endif">
                                <h2 class="mb-0 number-font" id='pending'>
                                    {{ $pending }}
                                </h2>
                            </a>
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
                            <h6 class="">Listing Approved</h6>
                            <!-- <a target="_blank" href="{{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => '3Y']) }}"> -->
                            <h2 class="mb-0 number-font text-success" id='approved'>{{ $approved }}</h2>
                            <!-- </a> -->
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
                            <h6 class="">Listing Rejected</h6>
                            <!-- <a target="_blank" href="{{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => '3Y']) }}"> -->
                            <h2 class="mb-0 number-font text-danger" id='rejected'>{{ $rejected }}</h2>
                            <!-- </a> -->
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

    <div class="page-header m-0">
        <h1 class="page-title">{{ __('Total Products ( Main Site )') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <a href="@if(auth()->user()->hasRole('Super Admin') && auth()->user()->hasRole('Super Management')) {{ route('inventory.index', ['startIndex' => 1, 'category' => 'Product']) }} @endif">
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

    <div class="page-header m-0">
        <h1 class="page-title">{{ __('Stock Health ( Main Site )') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total <b class='text-success'>In Stock</b></h6>
                                    <h2 class="mb-0 number-font text-success" id='inStock'>-</h2>
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
                                    <h6 class="">Total <b class='text-danger'>Out of Stock</b></h6>
                                    <h2 class="mb-0 number-font text-danger" id='outStock'>-</h2>
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
                                    <h6 class="">Total <b class='text-info'>Low Stock</b></h6>
                                    <h2 class="mb-0 number-font text-info" id='lowStock'>-</h2>
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
                                    <h6 class="">Total <b class='text-primary'>On Demand</b></h6>
                                    <h2 class="mb-0 number-font text-primary" id='demandStock'>-</h2>
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
        
        setTimeout(function(){
            getDraftedInventory();
        }, 2000)

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

                    
                    
                    getInventoryData();
                },
            });
        }

        function getInventoryData() {
            localStorage.setItem('totalPublished', 0);
            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Product'
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(result) {
                    // console.log(result);
                    $("#products").html(result);
                    localStorage.setItem('totalPublished', result);
                    getInventoryOutStockData();
                    
                },error:function(err){
                    console.log(err);
                }
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
                            
                            getInventoryLowStockData();
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
                            
                            getInventoryDemandStockData();
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
                            
                            getOneYearOldInventory();
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

        function getOneYearOldInventory() {
            localStorage.setItem('one-year-old', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Product',
                    updated_before: 1
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#one-year-old").html(result);
                    localStorage.setItem('one-year-old', result);
                    
                    getTwoYearOldInventory();
                },
            });
        }

        function getTwoYearOldInventory() {
            localStorage.setItem('two-year-old', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Product',
                    updated_before: 2
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#two-year-old").html(result);
                    localStorage.setItem('two-year-old', result);
                    
                    getThreeYearOldInventory();
                },
            });
        }

        function getThreeYearOldInventory() {
            localStorage.setItem('three-year-old', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Product',
                    updated_before: "3Y"
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#three-year-old").html(result);
                    localStorage.setItem('three-year-old', result);
                    
                    getSixMonthOldInventory();
                    
                    setTimeout(() => {
                        getInventoryInStockData();
                    }, 1000);
                },
            });
        }

        function getSixMonthOldInventory() {
            localStorage.setItem('six-month-old', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: 'Product',
                    updated_before: 6
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#six-month-old").html(result);
                    localStorage.setItem('six-month-old', result);
                },
            });
        }
    })
</script>

@endpush