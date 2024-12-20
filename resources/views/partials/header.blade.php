@php
$count = app('App\Models\UserListingCount');

$currentMonthDataApproved = $count->whereMonth('created_at', \Carbon\Carbon::now()->month)
->whereYear('created_at', \Carbon\Carbon::now()->year)
->where('user_id', auth()->user()->id)
->sum('approved_count');

$currentMonthDataCreated = $count->whereMonth('created_at', \Carbon\Carbon::now()->month)
->whereYear('created_at', \Carbon\Carbon::now()->year)
->where('user_id', auth()->user()->id)
->sum('create_count');

$startOfWeek = \Carbon\Carbon::now()->subDays(7);
$endOfWeek = \Carbon\Carbon::yesterday();

$currentWeekDataCreated = $count->whereBetween('created_at', [$startOfWeek, $endOfWeek])
->where('user_id', auth()->user()->id)
->sum('create_count');

$googleService = app('App\Services\GoogleService');

@endphp
<!-- app-Header -->
<div class="app-header header sticky">
    <div class="container-fluid main-container">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
            @if(app('App\Http\Controllers\Controller')->tokenIsExpired($googleService)) 
            <span class="p-1 text-white" style="background-color: green;border-radius: 5px;">
                Connected
            </span>
            @else   
            <span class="p-1 text-white" style="background-color: red;border-radius: 5px;">
                Disconnect
            </span>
            @endif
            <!-- sidebar-toggle-->
            <a class="logo-horizontal " href="index.html">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logodropdown d-flex profile-1-3.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>
            <div class="d-flex order-lg-2 ms-auto header-right-icons">
                <div class="dropdown d-none">
                    <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                        <i class="fe fe-search"></i>
                    </a>
                    <div class="dropdown-menu header-search dropdown-menu-start">
                        <div class="input-group w-100 p-2">
                            <input type="text" class="form-control" placeholder="Search....">
                            <div class="input-group-text btn btn-primary">
                                <i class="fe fe-search" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SEARCH -->
                <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                </button>
                <div class="navbar navbar-collapse responsive-navbar p-0">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                        @if(!auth()->user()->hasRole('Super Admin') && auth()->user()->show_health)
                        <label style="margin-right: 10px;font-family: system-ui;"><strong>ACCOUNT HEALTH:</strong>
                            @if($currentWeekDataCreated <= 120)
                                <span style="border: 1px solid red;padding: 5px;background-color: red;color: white;box-shadow: 1px 1px 3px black;">DEACTIVATION</span>
                                @elseif($currentWeekDataCreated >= 121 && $currentWeekDataCreated <= 149)
                                    <span style="border: 1px solid orange;padding: 5px;background-color: orange;color: black;box-shadow: 1px 1px 3px black;">AT RISK</span>
                                    @elseif($currentWeekDataCreated >= 150 && $currentWeekDataCreated <= 199)
                                        <span style="border: 1px solid yellow;padding: 5px;background-color: yellow;color: black;box-shadow: 1px 1px 3px black;">AVERAGE</span>
                                        @elseif($currentWeekDataCreated >= 200 && $currentWeekDataCreated <= 349)
                                            <span style="border: 1px solid lightgreen;padding: 5px;background-color: lightgreen;color: black;box-shadow: 1px 1px 3px black;">GOOD</span>
                                            @elseif($currentWeekDataCreated >= 350)
                                            <span style="border: 1px solid green;padding: 5px;background-color: green;color: white;box-shadow: 1px 1px 3px black;">EXCELLENT</span>
                                            @endif
                        </label>
                        @endif

                        <div class="dropdown d-flex profile-1">
                            <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                @if(auth()->user()->profile)
                                <img src="{{ auth()->user()->profile }}" alt="profile-user asdasd" class="avatar profile-user brround cover-image">
                                @else
                                <p class='avatar profile-user brround cover-image text-dark'>{{ auth()->user()->name[0]; }}</p>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <div class="drop-heading">
                                    <div class="text-center">
                                        <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ auth()->user()->name }}</h5>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="dropdown-icon fa fa-money"></i> Earnings (Approved) : ₹ {{$currentMonthDataApproved * auth()->user()->posting_rate}}
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="dropdown-icon fa fa-money"></i> Expected Earnings (Created) : ₹ {{ $currentMonthDataCreated * auth()->user()->posting_rate}}
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="dropdown-icon fe fe-user"></i> Profile
                                </a>
                                <!-- <a class="dropdown-item" href="{{ route('profile.listing') }}">
                                    <i class="dropdown-icon fe fe-user"></i> Listings
                                </a> -->
                                <!-- <a class="dropdown-item" href="email-inbox.html">
                                    <i class="dropdown-icon fe fe-mail"></i> Inbox
                                    <span class="badge bg-danger rounded-pill float-end">5</span>
                                </a>
                                <a class="dropdown-item" href="lockscreen.html">
                                    <i class="dropdown-icon fe fe-lock"></i> Lockscreen
                                </a> -->
                                <a class="dropdown-item" href="{{route('change.user.password')}}">
                                    <i class="dropdown-icon fe fe-lock"></i> Change Password
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="return confirm('{{ __('Are you sure you want logout?') }}') ? document.getElementById('logout').submit() : false;">
                                    <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                                </a>

                                <form action="{{ route('logout') }}" id="logout" method="post">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /app-Header -->