@extends('layouts.master')

@section('title', __('Review Inventory | Published ( M/S )'))

@push('css')
<style>
    ul {
        justify-content: end;
    }

    #basic-datatable_info {
        display: none;
    }

    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    hr {
        border: 1px solid #ccc;
        width: 100%;
        height: 0px !important;
        margin-top: 0px;
    }

    .alert-msg {
        background-color: #808007;
        color: white;
    }
</style>
@endpush

@section('content')

<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Review Inventory | Published ( M/S )</h3>
                    <div class="d-flex" style="grid-gap: 10px;;">
                        <form action="{{ route('review_inventory_export') }}">
                            <input type="hidden" name="updated_before" value="{{ request()->updated_before }}" />
                            @can('Inventory > Review Inventory (M/S) > Export')
                            <button class="btn btn-success btn-icon ml-2 add_icon w-100 d-flex align-items-center" style="grid-gap: 10px;"><i class="fa fa-download"></i> Export</button>
                            @endcan
                        </form>

                        <form action="" method="get" id='form'>
                            <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                            <input type="hidden" value="{{ request()->updated_before ?? 3 }}" name='updated_before'>
                            <select class="form-control w-100" id='category' name="category">
                                <option value="Product" {{ request()->category == 'Product' ? 'selected' : '' }}>In Stock</option>
                                <option value="Stk_o" {{ request()->category == 'Stk_o' ? 'selected' : '' }}>Out of Stock (Stk_o)</option>
                                <option value="stock__out" {{ request()->category == 'stock__out' ? 'selected' : '' }}>Out of Stock (stock__out)</option>
                                <option value="Stk_d" {{ request()->category == 'Stk_d' ? 'selected' : '' }}>On Demand Stock (Stk_d)</option>
                                <option value="stock__demand" {{ request()->category == 'stock__demand' ? 'selected' : '' }}>On Demand Stock (stock__demand)</option>
                                <option value="Stk_l" {{ request()->category == 'Stk_l' ? 'selected' : '' }}>Low Stock (Stk_l)</option>
                                <option value="stock__low" {{ request()->category == 'stock__low' ? 'selected' : '' }}>Low Stock (stock__low)</option>
                            </select>
                            <input type="hidden" value="{{ request()->paging ?? 25 }}" name='updated_before'>
                        </form>

                        <form action="" method="get" id='pagingform' class="w-25">
                            <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                            <input type="hidden" value="{{ request()->updated_before ?? 3 }}" name='updated_before'>
                            <input type="hidden" value="{{ request()->category ?? Product }}" name='category'>
                            <select class="form-control w-100" id='paging' name="paging">
                                <option value="25" {{ request()->paging == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request()->paging == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request()->paging == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <span class="d-flex justify-content-center mb-4 alert-msg text-center align-items-center" style='grid-gap:5px;'>
                        <i class='fa fa-warning'></i>
                        <strong>Alert:</strong> 
                        <span>First, click on "3 Years," then update the listings. Next, proceed to "2 Years" & Finally Hit "1 Year"</span>
                    </span>
                    <hr />
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Product ID') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($googlePosts['paginator'] as $key => $googlePost)
                                @php
                                $doc = new \DOMDocument();
                                if(((array)($googlePost->content))['$t']){
                                @$doc->loadHTML(((array)($googlePost->content))['$t']);
                                }
                                $td = $doc->getElementsByTagName('td');
                                $price = explode('-', $td->item(1)->textContent ?? '');
                                $selling = $price[0]??0;
                                $mrp = $price[1]??0;
                                $image = $doc->getElementsByTagName("img")?->item(0)?->getAttribute('src');
                                $productId = explode('-', ((array)$googlePosts['paginator'][$key]->id)['$t'])[2];
                                $productTitle = ((array)$googlePosts['paginator'][$key]->title)['$t'];
                                $published = ((array)$googlePosts['paginator'][$key]->published)['$t'];
                                $updated = ((array)$googlePosts['paginator'][$key]->updated)['$t'];
                                @endphp
                                <tr>
                                    <td>{{ request()->startIndex++ }}</td>
                                    <td>
                                        @if(isset($googlePost->category) && (in_array('Stk_o', $googlePost->category) || in_array('stock__out', $googlePost->category)))
                                        {{ 'Out of Stock' }}
                                        @elseif(isset($googlePost->category) && (in_array('Stk_d', $googlePost->category) || in_array('stock__demand', $googlePost->category)))
                                        {{ 'On Demand' }}
                                        @elseif(isset($googlePost->category) && in_array('Stk_b', $googlePost->category))
                                        {{ 'Pre Booking' }}
                                        @elseif(isset($googlePost->category) && (in_array('Stk_l', $googlePost->category) || in_array('stock__low', $googlePost->category)))
                                        {{ 'Low Stock' }}
                                        @else {{ 'In Stock' }}
                                        @endif
                                    </td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $image }}" alt="Product Image" /></td>
                                    <td>
                                        @if($productTitle)
                                        <a href="{{ $googlePost->link[4]->href??'' }}" target="_blank" style="white-space: normal;">
                                            {{ $productTitle }}
                                        </a>
                                        @else
                                        <button type="button" class="btn-sm btn btn-danger">Error</button>
                                        @endif
                                    </td>
                                    <td>@if($selling) {{ $selling ? '₹'.$selling : ''  }} @else <button class="btn btn-sm btn-danger">Error</button>@endif</td>
                                    <td>@if($mrp) {{ $mrp ? '₹'.$mrp : '' }} @else <button class="btn btn-sm btn-danger">Error</button> @endif</td>
                                    <td>
                                        <a href="{{ $googlePost?->link[4]->href??'' }}" target="_blank">
                                            {{ $productId }}
                                        </a>
                                    </td>
                                    @php
                                    $categories = collect($googlePost->category??[])->toArray();
                                    $listing = app("\App\Models\Listing")->where('product_id', $productId)->first();
                                    @endphp
                                    <td>
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ implode(", ", $categories) }}">
                                            {{ count($categories ?? []) }}
                                            </button>
                                    </td>

                                    <td>{{ date("d-m-Y h:i A", strtotime($published)) }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($updated)) }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example" style="grid-gap: 5px;">
                                            @if($mrp && $selling && $productTitle)
                                            @can('Inventory -> Manage Inventory -> Edit')
                                            <a href="{{ route('listing.edit', $productId) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            @endcan
                                            @endif

                                            @can('Inventory -> Manage Inventory -> Edit ( DB )')
                                            @if(!$listing)
                                            <a href="{{ route('listing.edit.database', $productId) }}" class="btn btn-sm btn-primary">Edit (DB)</a>
                                            @endif
                                            @endcan

                                            @can('Inventory -> Manage Inventory -> Delete')
                                            <form action="{{ route('listing.destroy', $productId) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <nav aria-label="Page navigation example">
                        @if(request()->route()->getName() == 'inventory.review')
                        <ul class="pagination">
                            @if($googlePosts['prevStartIndex'] > 0) <li class="page-item"><a class="page-link" href="{{ route('inventory.review', ['pageToken' => $googlePosts['prevPageToken'], 'startIndex' => $googlePosts['prevStartIndex'], 'category' => request()->category, 'updated_before' => request()->updated_before]) }}">Previous</a></li> @endif
                            <li class="page-item"><a class="page-link" href="{{ route('inventory.review', ['pageToken' => $googlePosts['nextPageToken'], 'startIndex' => $googlePosts['startIndex'], 'category' => request()->category, 'updated_before' => request()->updated_before]) }}">Next</a></li>
                        </ul>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="/assets/js/table-data.js"></script>

<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- TIMEPICKER JS -->
<script src="/assets/plugins/time-picker/jquery.timepicker.js"></script>
<script src="/assets/plugins/time-picker/toggles.min.js"></script>

<!-- DATEPICKER JS -->
<script src="/assets/plugins/date-picker/date-picker.js"></script>
<script src="/assets/plugins/date-picker/jquery-ui.js"></script>

<!-- COLOR PICKER JS -->
<script src="/assets/plugins/pickr-master/pickr.es5.min.js"></script>

<!-- FORMELEMENTS JS -->
<script src="/assets/js/form-elements.js"></script>

<script>
    $(document).ready(function() {
        //______Basic Data Table
        $('#basic-datatable').DataTable({
            "paging": false
        });

        $("#category").on("change", function() {
            $("#form").submit();
        });

        $("#paging").on("change", function() {
            $("#pagingform").submit();
        });

        $("#basic-datatable_wrapper .col-sm-12:first").html('<div class="d-flex"><label class="m-1"><input type="radio" id="six" @if(request()->updated_before == 6) checked=checked @endif name="ago"/>6 Months <span id="count-six">(0)</span></label><label class="m-1"><input type="radio" @if(request()->updated_before == 1) checked=checked @endif id="one" name="ago" />1 Year <span id="count-one">(0)</span></label><label class="m-1"><input type="radio" @if(request()->updated_before == 2) checked=checked @endif id="two" name="ago" />2 Year <span id="count-two">(0)</span></label><label class="m-1"><input type="radio" @if(request()->updated_before == "3Y") checked=checked @endif id="three" name="ago" />3 Year <span id="count-three">(0)</span></label></div>');

        $('#basic-datatable_wrapper').on('click', '#six', function() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            params.delete('updated_before');
            let allParams = params.toString() + '&updated_before=6';

            window.location.href = url.origin + url.pathname + "?" + allParams;
        })

        $('#basic-datatable_wrapper').on('click', '#one', function() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            params.delete('updated_before');
            let allParams = params.toString() + '&updated_before=1';

            window.location.href = url.origin + url.pathname + "?" + allParams;
        });

        $('#basic-datatable_wrapper').on('click', '#two', function() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            params.delete('updated_before');
            let allParams = params.toString() + '&updated_before=2';

            window.location.href = url.origin + url.pathname + "?" + allParams;
        });

        $('#basic-datatable_wrapper').on('click', '#three', function() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            params.delete('updated_before');
            let allParams = params.toString() + '&updated_before=3Y';

            window.location.href = url.origin + url.pathname + "?" + allParams;
        });

        setTimeout(function() {
            getSixMonthOldInventory();
        }, 1000);

        function getTwoYearOldInventory() {
            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: "{{request()->category}}",
                    updated_before: 2
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#count-two").html("(" + result + ")");

                    get3YearOldInventory();
                },
            });
        }

        function getOneYearOldInventory() {
            // localStorage.setItem('one-year-old', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: "{{request()->category}}",
                    updated_before: 1
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#count-one").html("(" + result + ")");

                    getTwoYearOldInventory();
                    // localStorage.setItem('one-year-old', result);
                },
            });
        }

        function getSixMonthOldInventory() {
            // localStorage.setItem('six-month-old', 0);

            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: "{{request()->category}}",
                    updated_before: 6
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#count-six").html("(" + result + ")");
                    // localStorage.setItem('six-month-old', result);
                    getOneYearOldInventory();
                },
            });
        }

        function get3YearOldInventory() {
            $.ajax({
                type: "GET",
                url: "{{ route('get.posts.count') }}",
                data: {
                    category: "{{request()->category}}",
                    updated_before: "3Y"
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    $("#count-three").html("(" + result + ")");
                },
            });
        }
    })
</script>

@endpush