@extends('layouts.master')

@section('title', __('Manage Inventory'))

@push('css')
<style>
    ul {
        justify-content: end;
    }
</style>
@endpush

@section('content')
@can('Inventory access')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <!-- <form action="" method="get">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4 filters">
                            <div class="col-md-12">
                                <h5>Filters</h5>
                            </div>

                            <input type="hidden" name="type" value="search">

                            <div class="col-md-3">
                                <select class="form-control" id='status' name="status">
                                    <option value="">Status</option>
                                    <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="draft" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <input class="form-control" id='q' name="q" placeholder="Search By Full Name" value="{{ request('q') }}" />
                            </div>

                            <div class="col-lg-3">
                                <div class="wd-200 mg-b-30">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <span class="fa fa-calendar tx-16 lh-0 op-6"></span>
                                        </div><input class="form-control fc-datepicker" value="{{ request('startDate') }}" placeholder="Start Date" name="startDate" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="wd-200 mg-b-30">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <span class="fa fa-calendar tx-16 lh-0 op-6"></span>
                                        </div>
                                        <input class="form-control fc-datepicker" value="{{ request('endDate') }}" placeholder="End Date" name="endDate" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Filter</button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-danger">Reset</a>
                    </div>
                </div>
            </form> -->

            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Manage Inventory</h3>
                    <form action="" method="get" id='form'>
                        <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                        <select class="form-control w-100" id='category' name="category">
                            <option value="">In Stock</option>
                            <option value="Stk_o" {{ request()->category == 'Stk_o' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="Stk_d" {{ request()->category == 'Stk_d' ? 'selected' : '' }}>On Demand Stock</option>
                            <option value="Stk_l" {{ request()->category == 'Stk_l' ? 'selected' : '' }}>Low Stock</option>
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
                                    <th>{{ __('Product ID') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($googlePosts['paginator'] as $key => $googlePost)
                                @php
                                $doc = new \DOMDocument();
                                @$doc->loadHTML(((array)($googlePost->content))['$t']);
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
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        @if(isset($googlePost->category) && in_array('Stk_o', $googlePost->category))
                                        {{ 'Out of Stock' }}
                                        @elseif(isset($googlePost->category) && in_array('Stk_d', $googlePost->category))
                                        {{ 'On Demand' }}
                                        @elseif(isset($googlePost->category) && in_array('Stk_b', $googlePost->category))
                                        {{ 'Pre Booking' }}
                                        @elseif(isset($googlePost->category) && in_array('Stk_l', $googlePost->category))
                                        {{ 'Low Stock' }}
                                        @else {{ 'In Stock' }}
                                        @endif
                                    </td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $image }}" alt="Product Image" /></td>
                                    <td><a href="{{ $googlePost->link[4]->href }}" target="_blank">{{ $productId }}</a></td>
                                    <td>@if($productTitle)<a href="{{ $googlePost->link[4]->href }}" target="_blank"> {{ $productTitle }}</a>@else Edited By Dashboard @endif</td>
                                    <td>{{ count($googlePost->category??[]) }}</td>
                                    <td>{{ $mrp ? '₹'.$mrp : 'Edited By Dashboard' }}</td>
                                    <td>{{ $selling ? '₹'.$selling : 'Edited By Dashboard'  }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($published)) }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($updated)) }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @if($mrp && $selling && $productTitle)
                                            @can('Inventory edit')
                                            <a href="{{ route('listing.edit', $productId) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            @endcan
                                            @endif

                                            @can('Inventory delete')
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
@endcan
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

        // $("#specification").on('change', function(e) {
        //     $('#field').on('input', function() {
        //         var field = $(this).val();
        //         var value = $('#specification').val();

        //         dataTable.column(value).search(field).draw();
        //     })

        // });

        // $("#status").on("change", function(e) {
        //     var status = $(this).val();
        //     $("#status").val(status);
        //     dataTable.column(1).search(status).draw();
        // });

        $("#category").on("change", function() {
            $("#form").submit();
        })
    })
</script>

@endpush