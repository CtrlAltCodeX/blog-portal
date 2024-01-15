@php
    $userInfo = app('App\Models\SiteSetting')->first();

@endphp

<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="index.html">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logo-2.png') }}" class="header-brand-img light-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img light-logo1" alt="logo" width="50" height="50">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
          
                @can('Dashboard Access')
                    <li class="sub-category">
                        <h3>{{ __('Main') }}</h3>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('dashboard') }}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">{{ __('Dashboard') }}</span></a>
                    </li>
                @endcan
                
                <li class="sub-category">
                    <h3>{{ __('Accounts') }}</h3>
                </li>

                @can('Listing access')
                    <li class="slide {{ (request()->is('admin/listing/*') || request()->is('admin/listing') || request()->is('admin/find-products/amazon')) ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Listing') }}</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                            @can('Listing create')
                                <li><a href="{{ route('listing.create') }}" class="slide-item {{ (request()->is('admin/listing/create')) ? 'active' : '' }}">{{ __('Create Listing') }}</a></li>
                            @endcan
                            <li><a href="{{ route('amazon.find') }}" class="slide-item {{ (request()->is('admin/find-products/amazon')) ? 'active' : '' }}">{{ __('Create Listing (Amazon scrap)') }}</a></li>
                            @can('Listing access')
                                <!-- <li><a href="{{ route('listing.index') }}" class="slide-item {{ (request()->is('admin/listing')) ? 'active' : '' }}">{{ __('Catalogue') }}</a></li> -->
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('Inventory access')
                    <li class="slide {{ request()->is('admin/inventory') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Manage all Inventory') }}</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                            <li><a href="{{ route('inventory.index') }}" class="slide-item {{ request()->is('admin/inventory') ? 'active' : '' }}">{{ __('Manage all Inventory') }}</a></li>
                        </ul>
                    </li>
                @endcan

                @can('User access')
                    <li class="slide {{ (request()->is('admin/users/*') || request()->is('admin/users')) ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Users') }}</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                            
                            @can('User create')
                             <li><a href="{{ route('users.create') }}" class="slide-item {{ request()->is('admin/users/create') ? 'active' : '' }}">{{ __('Create Users') }}</a></li>
                            @endcan
                            
                            @can('User approved')
                                <li><a href="{{ route('verified.users') }}" class="slide-item {{ request()->is('admin/users/verified/approved') ? 'active' : '' }}">{{ __('Approved Users') }}</a>
                                </li>
                            @endcan
                            
                            @can('User access')
                                <li><a href="{{ route('users.index') }}" class="slide-item {{ (request()->is('admin/users') || request()->is('admin/users/*/edit')) ? 'active' : '' }}">{{ __('All Users') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('Role access')
                    <li class="slide {{ (request()->is('admin/roles/*') || request()->is('admin/roles')) ? 'is-expanded' : '' }}">
                        <a class="side-menu__item  {{ request()->is('admin/roles*') ? 'active is-expanded' : '' }}" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-lock"></i><span class="side-menu__label">{{ __('Role & Permission') }}</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            @can('Role create')
                                <li>
                                    <a href="{{ route('roles.create') }}" class="slide-item  {{ request()->is('admin/roles/create') ? 'active' : '' }}">{{ __('Create New Role') }}</a>
                                </li>
                            @endcan

                            @can('Role access')
                                <li>
                                    <a href="{{ route('roles.index') }}" class="slide-item  {{ (request()->is('admin/roles') || request()->is('admin/roles/edit')) ? 'active' : '' }}">{{ __('Assign Permissions to Roles') }}</a>
                                </li>
                            @endcan

                            @can('Role access')
                                <li>
                                    <a href="{{ route('view.roles') }}" class="slide-item  {{ (request()->is('admin/roles/all/view')) ? 'active' : '' }}">{{ __('View all Roles & Permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @if(auth()->user()->can('Site Access') || auth()->user()->can('Configure Blog'))
                    <li class="slide {{ (request()->is('admin/settings/*') || request()->is('admin/settings')) ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Settings') }}</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                            
                            @can('Site Access')
                                <li><a href="{{ route('settings.site') }}" class="slide-item {{ request()->is('admin/settings/site') ? 'active' : '' }}">{{ __('Site settings') }}</a></li>
                            @endcan

                            @can('Configure Blog')
                                <li><a href="{{ route('settings.blog') }}" class="slide-item {{ request()->is('admin/settings/blog') ? 'active' : '' }}">{{ __('Confiure with blog') }}</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>