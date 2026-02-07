@php
$userInfo = app('App\Models\SiteSetting')->first();

@endphp

<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar" style="overflow: scroll;">
        <div class="side-header">
            <a class="header-brand1" href="/admin/dashboard">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img light-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img light-logo1" alt="logo" width="50" height="50">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>{{ __('Main') }}</h3>
                </li>
                
                @can('Dashboard')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('dashboard') }}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">{{ __('Dashboard') }}</span></a>
                </li>
                @endcan
                
                @can('Analytics Dashboard')
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('graphical.dashboard') }}">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">{{ __('Analytics Dashboard') }}</span>
                    </a>
                </li>
                @endcan

                <li class="sub-category">
                    <h3>{{ __('Information') }}</h3>
                </li>

                @can('Listing (Main Menu)')
                <li class="slide {{ (request()->is('admin/modify-listing*') || request()->is('admin/listing/*') || request()->is('admin/listing') || request()->is('admin/database-listing') || request()->is('admin/database-listing/*') || request()->is('admin/publish/pending') || request()->is('get/ai/description')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-list"></i><span class="side-menu__label">{{ __('Product Listing') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('modify-listing.index') }}" class="slide-item {{ (request()->is('admin/modify-listing')) ? 'active' : '' }}">{{ __('Modify Listing') }}</a></li>
                        <li><a href="{{ route('modify-listing.requested') }}" class="slide-item {{ (request()->is('admin/modify-listing/requested')) ? 'active' : '' }}">{{ __('Requested Listings') }}</a></li>
                        <li><a href="{{ route('modify-listing.approval') }}" class="slide-item {{ (request()->is('admin/modify-listing/approval')) ? 'active' : '' }}">{{ __('Approval Section') }}</a></li>
                        
                        @can('Product Listing > AI Chat Bots')
                        <li><a href="{{ route('ai.description') }}" class="slide-item {{ request()->is('get/ai/description') ? 'active' : '' }}">{{ __('AI Chat Bots') }}</a></li>
                        @endcan
                        
                        @can('Listing -> Search Listing')
                        <li><a href="{{ route('listing.search', ['publisher_rates' => 'without_offer']) }}" class="slide-item {{ (request()->is('admin/search')) ? 'active' : '' }}">{{ __('Search Listing ( M/S )') }}</a></li>
                        @endcan
                        
                        @can('Product Listing > Bulk Listing Upload')
                        <li><a href="{{ route('upload-file.options') }}" class="slide-item {{ (request()->is('admin/upload-file')) ? 'active' : '' }}">{{ __('Bulk Listing Upload') }}</a></li>
                        @endcan
                        
                        @can('Product Listing > Bulk Listing Review (Edit)')
                        <li><a href="{{ route('view.upload', ['type' => 1]) }}" class="slide-item {{ (request()->is('admin/upload-file')) ? 'active' : '' }}">{{ __('Bulk Listing Review') }}</a></li>
                        @endcan
                        
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        
                        @can('Listing Create (M/S)')
                        <li><a href="{{ route('listing.create') }}" class="slide-item {{ (request()->is('admin/images/*')) ? 'active' : '' }}">{{ __('Create New Listing ( M/S )') }}</a></li>
                        @endcan
                        @can('Listing create ( DB )')
                        <li><a href="{{ route('database-listing.create') }}" class="slide-item {{ (request()->is('admin/database-listing/create')) ? 'active' : '' }}">{{ __('Create New Listing ( DB )') }}</a></li>
                        @endcan
                        @can('RA-Pending Listing (DB)')
                        <li><a href="{{ route('database-listing.index', ['status' => 0, 'startIndex' => 1, 'category' => '', 'user' => 'all','paging'=>25]) }}" class="slide-item {{ (request()->is('admin/database-listing')) ? 'active' : '' }}">{{ __('RA | Pending Listing (DB)') }}</a></li>
                        @endcan
                        @can('RA-Updated Listings (MS)')
                        <li><a href="{{ route('publish.pending', ['status' => 0, 'startIndex' => 1, 'category' => '', 'user' => 'all','paging'=>25]) }}" class="slide-item {{ (request()->is('admin/publish/pending')) ? 'active' : '' }}">{{ __('RA | Updated Listings (MS)') }}</a></li>
                        @endcan
                        
                        <!--<li>-->
                        <!--    <a href="{{ route('candidates.enquiries') }}" class="slide-item {{ (request()->is('admin/candidates/enquiries')) ? 'active' : '' }}">-->
                        <!--        {{ __('Candidate Enquiries') }}-->
                        <!--    </a>-->
                        <!--</li>-->
                    </ul>
                </li>
                @endcan

                @can('Image Creation (Main Menu)')
                <li class="slide {{ (request()->is('admin/images/single/create') || request()->is('admin/images/combo/create') || request()->is('admin/images/gallery')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-list"></i><span class="side-menu__label">{{ __('Images Creation') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        @can('Image Creation -> Single Image Maker')
                        <li><a href="{{ route('image.single.create', ['maker' => 'wo-watermark', 'size' => 'basic']) }}" class="slide-item {{ (request()->is('admin/images/single/create')) ? 'active' : '' }}">{{ __('Single Image Maker') }}</a></li>
                        @endcan
                        @can('Image Creation -> Combo Image Maker')
                        <li><a href="{{ route('image.combo.create', ['maker' => 'wo-watermark', 'size' => 'basic']) }}" class="slide-item {{ (request()->is('admin/images/combo/create')) ? 'active' : '' }}">{{ __('Combo Image Maker') }}</a></li>
                        @endcan
                        @can('Image Creation -> Gallery ( DB )')
                        <li><a href="{{ route('image.gallery', ['count' => 15, 'page' => '']) }}" class="slide-item {{ (request()->is('admin/images/gallery')) ? 'active' : '' }}">{{ __('Gallery List Page ( DB )') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('Inventory (Main Menu)')
                <li class="slide {{ (request()->is('admin/inventory') || request()->is('admin/inventory/drafted') || request()->is('admin/inventory/review') || request()->is('admin/google/products/list') || request()->is('admin/profile/listings') || request()->is('admin/publishers')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">{{ __('Inventory') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        @can('Inventory -> Blogger Articles')
                        <li><a href="{{ route('articles.index') }}" class="slide-item {{ request()->is('admin/articles') ? 'active' : '' }}">{{ __('Blogger Articles') }}</a></li>
                        @endcan
                        @can('Inventory -> Manage Inventory')
                        <li><a href="{{ route('inventory.index', ['startIndex' => 1, 'category' => 'Product','paging'=>25]) }}" class="slide-item {{ request()->is('admin/inventory') ? 'active' : '' }}">{{ __('Manage Inventory ( M/S )') }}</a></li>
                        @endcan
                        @can('Inventory -> Drafted Inventory')
                        <li><a href="{{ route('inventory.drafted') }}" class="slide-item {{ request()->is('admin/inventory/drafted') ? 'active' : '' }}">{{ __('Drafted Inventory ( M/S )') }}</a></li>
                        @endcan
                        
                        @can('Inventory -> Low Pricing Error ( DB )')
                        <li><a href="{{ route('inventory.review.price.issue', ['paging' => 25]) }}" class="slide-item {{ request()->is('admin/review/price/issue') ? 'active' : '' }}">{{ __('Low Pricing Error ( DB )') }}</a></li>
                        @endcan
                        
                        @can('Inventory -> Publishers')
                        <li><a href="{{ route('listing.publishers', ['paging' => '25']) }}" class="slide-item {{ request()->is('admin/publishers') ? 'active' : '' }}">{{ __('Affected Publishers List') }}</a></li>
                        @endcan
                        
                        @can('Inventory -> Under Review Inventory')
                        <li><a href="{{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => 3,'paging'=>25]) }}" class="slide-item {{ request()->is('admin/inventory/review') ? 'active' : '' }}">{{ __('Review Inventory ( M/S )') }}</a></li>
                        @endcan
                        @can('Inventory -> Counts Report')
                        <li><a href="{{ route('profile.listing', ['user' => 'all', 'status' => 0, 'status_listing' => 'Created']) }}" class="slide-item {{ (request()->is('admin/profile/listings')) ? 'active' : '' }}">{{ __('Listing Counts Report ( DB )') }}</a></li>
                        @endcan
                        
                        <li><a href="{{ route('users.count') }}" class="slide-item {{ (request()->is('admin/users/count/users')) ? 'active' : '' }}">{{ __('Detail Work Reports') }}</a></li>
                    </ul>
                </li>
                @endcan

                @can('User Details (Main Menu)')
                <li class="slide {{ (request()->is('admin/users/*') || request()->is('admin/users')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Users Details') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>

                        @can('User New create')
                        <li><a href="{{ route('users.create') }}" class="slide-item {{ request()->is('admin/users/create') ? 'active' : '' }}">{{ __('Create New Users') }}</a></li>
                        @endcan

                        @can('Allot User Roles')
                        <li><a href="{{ route('verified.users', ['users' => 50]) }}" class="slide-item {{ request()->is('admin/users/verified/approved') ? 'active' : '' }}">{{ __('Allot User Roles') }}</a>
                        </li>
                        @endcan

                        @can('User Details -> All Users List')
                        <li><a href="{{ route('users.index', ['users' => 50]) }}" class="slide-item {{ (request()->is('admin/users') || request()->is('admin/users/*/edit')) ? 'active' : '' }}">{{ __('All Users List') }}</a></li>
                        @endcan
                        
                        <!--<li><a href="{{ route('candidates.enquiries') }}" class="slide-item {{ (request()->is('admin/candidates/enquiries')) ? 'active' : '' }}">{{ __('Enquiries List') }}</a></li>-->
                    </ul>
                </li>
                @endcan

                @if(auth()->user()->can('Roles & Permissions (Main Menu)'))
                <li class="slide {{ (request()->is('admin/roles/*') || request()->is('admin/roles')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item  {{ request()->is('admin/roles*') ? 'active is-expanded' : '' }}" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-lock"></i><span class="side-menu__label">{{ __('Roles & Permissions') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        @can('Role create')
                        <li>
                            <a href="{{ route('roles.create') }}" class="slide-item  {{ request()->is('admin/roles/create') ? 'active' : '' }}">{{ __('Create New Roles') }}</a>
                        </li>
                        @endcan
                        @can('Roles & Permissions -> Assign Permissions to Roles')
                        <li>
                            <a href="{{ route('roles.index') }}" class="slide-item  {{ (request()->is('admin/roles') || request()->is('admin/roles/edit')) ? 'active' : '' }}">{{ __('Assign Permissions to Roles') }}</a>
                        </li>
                        @endcan

                        @can('Roles & Permissions -> View All Roles & Permissions')
                        <li>
                            <a href="{{ route('view.roles') }}" class="slide-item  {{ (request()->is('admin/roles/all/view')) ? 'active' : '' }}">{{ __('View All Roles & Permissions') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                
                @can('Lead/Job Application')
                <li class="slide {{ (request()->is('admin/candidates/enquiries')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fa fa-gear"></i><span class="side-menu__label">{{ __('Leads/Job Applications') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        @can('Lead/Job Application -> Job Enquiry List')
                        <li><a href="{{ route('candidates.enquiries') }}" class="slide-item {{ (request()->is('admin/candidates/enquiries')) ? 'active' : '' }}">{{ __('Job Enquiry List') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @if(auth()->user()->can('Settings (Main Menu)'))
                <li class="slide {{ (request()->is('admin/settings/*') || request()->is('admin/settings') || request()->is('admin/backup/emails') || request()->is('admin/backup/logs') || request()->is('admin/subcategories') || request()->is('admin/categories')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fa fa-gear"></i><span class="side-menu__label">{{ __('Settings') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>

                        @can('Settings -> Site Access')
                        <li><a href="{{ route('settings.site') }}" class="slide-item {{ request()->is('admin/settings/site') ? 'active' : '' }}">{{ __('Site Settings') }}</a></li>
                        @endcan
                        
                        @can('Settings -> Site Access')
                        <li><a href="{{ route('settings.policies') }}" class="slide-item {{ request()->is('admin/settings/policies/term') ? 'active' : '' }}">{{ __('Policies Pages') }}</a></li>
                        @endcan

                        @can('Settings -> Backup E-Mail')
                        <li><a href="{{ route('settings.emails') }}" class="slide-item {{ request()->is('admin/backup/emails') ? 'active' : '' }}">{{ __('Backup E-Mail') }}</a></li>
                        @endcan

                        @can('Settings -> Backup Logs & Links')
                        <li><a href="{{ route('backup.logs') }}" class="slide-item {{ request()->is('admin/backup/logs') ? 'active' : '' }}">{{ __('Backup Logs & Links') }}</a></li>
                        @endcan

                        @can('Settings -> Validations')
                        <li><a href="{{ route('settings.keywords.valid') }}" class="slide-item {{ request()->is('admin/names/validations') ? 'active' : '' }}">{{ __('Validations') }}</a></li>
                        @endcan

                        @can('Settings -> Configure Blog')
                        <li><a href="{{ route('settings.blog') }}" class="slide-item {{ request()->is('admin/settings/blog') ? 'active' : '' }}">{{ __('Configure API') }}</a></li>
                        @endcan
                        
                        <li><a href="{{ route('developers.index', ['users' => 50]) }}" class="slide-item {{ request()->is('admin/developers*') ? 'active' : '' }}">{{ __('Developer API Accounts') }}</a></li>
                        
                        @can('Settings -> Categories')
                        <li><a href="{{ route('categories.index') }}" class="slide-item {{ request()->is('admin/categories') ? 'active' : '' }}">{{ __('Categories') }}</a></li>
                        @endcan
                        
                        @can('Settings -> Sub Categories')
                        <li><a href="{{ route('subcategories.index') }}" class="slide-item {{ request()->is('admin/subcategories') ? 'active' : '' }}">   {{ __('Sub Categories') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                
                @can('List Post')
                <!--<li class="slide {{ (request()->is('admin/posts')) ? 'is-expanded' : '' }}">-->
                <!--    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fa fa-gear"></i><span class="side-menu__label">{{ __('Posts') }}</span><i class="angle fe fe-chevron-right"></i></a>-->
                <!--    <ul class="slide-menu">-->
                <!--        @can('Create Post')-->
                <!--        <li><a href="{{ route('posts.create') }}" class="slide-item {{ request()->is('admin/create') ? 'active' : '' }}">{{ __('Create Post') }}</a></li>-->
                <!--        @endcan-->
                <!--        @can('List Post')-->
                <!--        <li><a href="{{ route('posts.index', ['status' => 'pending_recent']) }}" class="slide-item {{ request()->is('admin/posts') ? 'active' : '' }}">{{ __('Post') }}</a></li>-->
                <!--        @endcan-->
                <!--    </ul>-->
                <!--</li>-->
                @endcan
                <!--<li class="slide {{ (request()->is('admin/posts')) ? 'is-expanded' : '' }}">-->
                <!--    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fa fa-gear"></i><span class="side-menu__label">{{ __('Posts') }}</span><i class="angle fe fe-chevron-right"></i></a>-->
                <!--    <ul class="slide-menu">-->
                <!--        <li>-->
                <!--            <a href="{{ route('worktype.index') }}" class="slide-item {{ request()->is('admin/WorkType ') ? 'active' : '' }}">{{ __('Work Type') }}</a>-->
                <!--        </li>-->
                <!--        <li>-->
                <!--            <a href="{{ route('content.create') }}" class="slide-item {{ request()->is('admin/content/create') ? 'active' : '' }}">{{ __('Content Create') }}</a>-->
                <!--        </li>-->
                <!--        <li>-->
                <!--            <a href="{{ route('content.index') }}" class="slide-item {{ request()->is('admin/content/listing') ? 'active' : '' }}">{{ __('Content Listing') }}</a>-->
                <!--        </li>-->
                <!--        <li>-->
                <!--            <a href="{{ route('promotional.create') }}" class="slide-item {{ request()->is('admin/promotional/create') ? 'active' : '' }}">{{ __('Create Promotional') }}</a>-->
                <!--        </li>-->
                <!--        <li>-->
                <!--            <a href="{{ route('promotional.index') }}" class="slide-item {{ request()->is('admin/promotional/listing') ? 'active' : '' }}">{{ __('Promotional Listing') }}</a>-->
                <!--        </li>-->
                <!--        <li>-->
                <!--            <a href="{{ route('approval.list') }}" class="slide-item {{ request()->is('admin/approval/list ') ? 'active' : '' }}">{{ __('Approval List') }}</a>-->
                <!--        </li>-->
                <!--        <li>-->
                <!--            <a href="{{ route('pages.index') }}" class="slide-item {{ request()->is('admin/pages ') ? 'active' : '' }}">{{ __('Admin Required') }}</a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->
            </ul>
        </div>
    </div>
</div>