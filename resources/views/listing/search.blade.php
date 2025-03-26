@extends('layouts.master')

@section('title', __('Search Products ( M/S )'))

@push('css')
<style>
    ul {
        justify-content: end;
    }
</style>
@endpush
@section('content')

<div>
    @can('Product Listing > Search Listing (M/S) > Discount')
            <div class='row'>
                <div class='col-md-6'>
                    <div class="card">
                        <div class="card-header">
                            <h5>Find Publisher Discount</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('listing.search') }}" class="text-center">
                                <!--<label>To Begin Adding New Products</label><br>-->
                                <label>
                                    <h3><b>Enter Publication Name</b></h3>
                                </label>
                                <br />
                                <label>Write Correct Publisher Name to get Correct Results.</label><br>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" name="p" id="publisher-search" placeholder="Search by Publication Name" value="{{ request()->q }}" />
                                    <button type="submit" class="btn btn-primary mt-2 m-2">Search</button>
                                </div>
                                <ul id="suggestions" class="list-group position-absolute mt-1 z-10" style="z-index: 100; display: none;"></ul>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-6'>
                    @if (request()->p)
                    <div class="card">
                        <div class="card-header">
                            <h5>Publisher Discount Results:</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Publication Name') }}</th>
                                            <th>{{ __('Discount Information') }}</th>
                                            <th>{{ __('Location') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($publications as $publisher)
                                        <tr>
                                            <td>{{ $publisher->publication_name }}</td>
                                            <td>{{ $publisher->discount_information }} %</td>
                                            <td>{{ $publisher->location }}</td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endcan
            
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Search Products ( M/S )</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('listing.search') }}" class="text-center">
                        <label>To Begin Adding New Products</label><br>
                        <label>
                            <h3><b>Find The Product in EXAM360`s Catalog</b></h3>
                        </label>
                        <br/>
                        <label>Search Books Using <b>Book Keywords</b> for better Results.</label><br>
                        <input type="hidden" value="1" name="startIndex" />
                        <input type="hidden" value="Product" name="category" />
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control" name="q" placeholder="Search by Book Name or Descriptions" value="{{ request()->q }}" />
                            <button type="submit" class="btn btn-primary mt-2 m-2">Search</button>
                        </div>
                        <div class="row" style="font-size: 11px;">
                            <div class="col-md-6 text-center" style="border-right: 1px solid #ccc;">
                                <a href="{{ route('listing.create') }}" target="_blank" style="color:#008296;">Item Not Found? Create New Listing (M/S)</a>
                            </div>
                            <div class="col-md-6 text-center">
                                <a href="{{ route('database-listing.create') }}" target="_blank" style="color:#008296;">Item Not Found? Create New Listings (DB)</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (request()->q)
            <div class="card">
                <div class="card-header">
                    <h5>Displaying Search Results:</h5>
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
                                    $categories = collect($googlePost->category??[])->pluck('term')->toArray();
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
                                            @can('Listing -> Search Listing -> Edit')
                                            <a href="{{ route('listing.edit', $productId) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            @endcan
                                            @endif

                                            @can('Inventory -> Manage Inventory -> Edit ( DB )')
                                            @if(!$listing)
                                            <a href="{{ route('listing.edit.database', $productId) }}" class="btn btn-sm btn-primary">Edit (DB)</a>
                                            @endif
                                            @endcan

                                            @can('Listing -> Search Listing -> Delete')
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
                        @if(request()->route()->getName() == 'listing.search')
                        <ul class="pagination">
                            @if($googlePosts['prevStartIndex'] > 0) <li class="page-item"><a class="page-link" href="{{ route('listing.search', ['pageToken' => $googlePosts['prevPageToken'], 'startIndex' => $googlePosts['prevStartIndex'], 'category' => request()->category, 'q' => request()->q]) }}">Previous</a></li> @endif
                            <li class="page-item"><a class="page-link" href="{{ route('listing.search', ['pageToken' => $googlePosts['nextPageToken'], 'startIndex' => $googlePosts['startIndex'], 'category' => request()->category, 'q' => request()->q]) }}">Next</a></li>
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
            @endif
            
            
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
            "paging": false,
            'searching': false
        });

        $("#category").on("change", function() {
            $("#form").submit();
        })
    })
</script>

<script>
    $(document).ready(function() {
        $('#publisher-search').on('input', function() {
            const query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('getpublishernames') }}",
                    method: 'GET',
                    data: {
                        query
                    },
                    success: function(data) {
                        const suggestions = $('#suggestions');
                        suggestions.empty().show();
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                suggestions.append(`<li class="list-group-item suggestion-item">${item}</li>`);
                            });
                        } else {
                            suggestions.append(`<li class="list-group-item">No results found</li>`);
                        }
                    }
                });
            } else {
                $('#suggestions').hide();
            }
        });

        $(document).on('click', '.suggestion-item', function() {
            const selectedText = $(this).text();
            $('#publisher-search').val(selectedText);
            $('#suggestions').hide();
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('#publisher-search, #suggestions').length) {
                $('#suggestions').hide();
            }
        });
    });
</script>
@endpush

</script>

<style>
    #suggestions {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
    }

    #suggestions .suggestion-item:hover {
        background: #f0f0f0;
    }
</style>