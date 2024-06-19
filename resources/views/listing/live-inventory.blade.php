@extends('layouts.master')

@section('title', __('Manage Inventory | Published ( M/S )'))

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
</style>
@endpush

@section('content')

<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Manage Inventory | Published ( M/S )</h3>
                    <form action="" method="get" id='form'>
                        <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                        <select class="form-control w-100" id='category' name="category">
                            <option value="">In Stock</option>
                            <option value="Stk_o" {{ request()->category == 'Stk_o' ? 'selected' : '' }}>Out of Stock (Stk_o)</option>
                            <option value="stock__out" {{ request()->category == 'stock__out' ? 'selected' : '' }}>Out of Stock (stock__out)</option>
                            <option value="Stk_d" {{ request()->category == 'Stk_d' ? 'selected' : '' }}>On Demand Stock (Stk_d)</option>
                            <option value="stock__demand" {{ request()->category == 'stock__demand' ? 'selected' : '' }}>On Demand Stock (stock__demand)</option>
                            <option value="Stk_l" {{ request()->category == 'Stk_l' ? 'selected' : '' }}>Low Stock (Stk_l)</option>
                            <option value="stock__low" {{ request()->category == 'stock__low' ? 'selected' : '' }}>Low Stock (stock__low)</option>
                        </select>
                    </form>
                </div>

                <div class="card-body">
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
                        @if(request()->route()->getName() == 'inventory.index')
                        <ul class="pagination">
                            @if($googlePosts['prevStartIndex'] > 0) <li class="page-item"><a class="page-link" href="{{ route('inventory.index', ['pageToken' => $googlePosts['prevPageToken'], 'startIndex' => $googlePosts['prevStartIndex'], 'category' => request()->category]) }}">Previous</a></li> @endif
                            <li class="page-item"><a class="page-link" href="{{ route('inventory.index', ['pageToken' => $googlePosts['nextPageToken'], 'startIndex' => $googlePosts['startIndex'], 'category' => request()->category]) }}">Next</a></li>
                        </ul>
                        @elseif(request()->route()->getName() == 'inventory.drafted')
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="{{ route('inventory.drafted', ['pageToken' => $googlePosts['prevPageToken']]) }}">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="{{ route('inventory.drafted', ['pageToken' => $googlePosts['nextPageToken']]) }}">Next</a></li>
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
        })
    })
</script>

@endpush