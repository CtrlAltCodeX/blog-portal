@extends('layouts.master')

@section('title', __('Blogger Articles'))

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

    .user-label {
        display: flex;
        align-items: center;
        margin-left: 5px;
        margin-right: 5px;
        margin-top: 8px;
    }
</style>
@endpush

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Blogger Articles</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all" /></th>
                                    <!--<th>{{ __('-') }}</th>-->
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($articles as $key => $googlePost)
                                <tr id='{{$googlePost->id}}'>
                                    <td>
                                        <input type="checkbox" name="ids" class="checkbox-update" value="{{$googlePost->id}}" />
                                    </td>
                                    <td>{{ ++$key }}</td>
                                    <td class="status">{{substr($googlePost->error, 0, 20)}}</td>
                                    <td>
                                        @switch($googlePost->status)
                                        @case(0)
                                        <span class="text-success"><b>Pending</b></span>
                                        @break;
                                        @case(2)
                                        <span class="text-danger"><b>Rejected</b></span>
                                        @break;
                                        @endswitch
                                    <td>
                                        @if(isset($googlePost->categories) && (in_array('Stk_o', $googlePost->categories) || in_array('stock__out', $googlePost->categories)))
                                        {{ 'Out of Stock' }}
                                        @elseif(isset($googlePost->categories) && (in_array('Stk_d', $googlePost->categories) || in_array('stock__demand', $googlePost->categories)))
                                        {{ 'On Demand' }}
                                        @elseif(isset($googlePost->categories) && in_array('Stk_b', $googlePost->categories))
                                        {{ 'Pre Booking' }}
                                        @elseif(isset($googlePost->categories) && (in_array('Stk_l', $googlePost->categories) || in_array('stock__low', $googlePost->categories)))
                                        {{ 'Low Stock' }}
                                        @else {{ 'In Stock' }}
                                        @endif
                                    </td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $googlePost->images }}" alt="Product Image" /></td>
                                    <td>{{ $googlePost->title }}</td>
                                    <td>{{ '₹'.$googlePost->selling_price }}</td>
                                    <td>{{ '₹'.$googlePost->mrp }}</td>
                                    @php
                                    $categories = collect($googlePost->categories??[])->toArray();
                                    @endphp
                                    <td>
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ implode(", ", $categories) }}">
                                            {{ count($categories ?? []) }}
                                            </button>
                                    </td>
                                    <td>{{ $googlePost->created_by_user->name }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->created_at)) }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->updated_at)) }}</td>
                                    @if(request()->status != 2 && ( auth()->user()->can('Pending Listing ( DB ) -> Edit') || auth()->user()->can('Pending Listing ( DB ) -> Delete')  ))
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('Pending Listing ( DB ) -> Edit')
                                            <a href="{{ route('database-listing.edit', $googlePost->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('Pending Listing ( DB ) -> Delete')
                                            <form action="{{ route('database-listing.destroy', $googlePost->id) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>

                        {!! $articles->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function() {
        //______Basic Data Table
        $('#basic-datatable').DataTable({
            "paging": false
        });
    })
</script>

@endpush