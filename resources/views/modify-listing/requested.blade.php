@extends('layouts.master')

@section('title', __('Modify Listings Status'))

@section('content')
<style>
    .panel-tabs {
        grid-gap:10px;
    }
    
    .panel-tabs li a {
        background-color: lightgrey;
        color:black;
        padding: 1rem 1.8rem !important;
        border-bottom:0px !important;
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
        
    }
    
    .panel-tabs li a.active {
        color:white !important;
        background-color:#6c5ffc;
    }
</style>

<div class="main-container container-fluid">
    <div class="card">
        <div class="card-header">
            <h1 class="page-title">{{ __('Modify Listings Status') }}</h1>
        </div>

        <div class="card-body">
            <div class="tabs-menu1">
                <ul class="nav panel-tabs">
                    <li>
                        <a href="#tab1" class="active" data-bs-toggle="tab">
                            Request For Update ({{ $requested->count() }})
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-bs-toggle="tab">
                            Request Completed ({{ $completed->count() }})
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="tab-content">

                {{-- TAB 1 : Request For Update --}}
                <div class="tab-pane active" id="tab1">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>PRODUCT ID</th>
                                    <th>IMAGE</th>
                                    <th>Request Type</th>
                                    <th>PUBLISHER</th>
                                    <th>BOOK NAME</th>
                                    <th>MRP.</th>
                                    <th>SELLING</th>
                                    <th>STATUS</th>
                                    <th>Requested By</th>
                                    <th>Requested Time</th>
                                    @can('Product Listing -> Modified Listing Status ->  Delete Button')
                                    <th>ACTION</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requested as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product_id }}</td>
                                    <td>
                                        <a href="{{ $item->product->base_url ?? '#' }}" target="_blank">
                                            <img src="{{ $item->product->base_url ?? asset('assets/images/no-image.png') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </a>
                                    </td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->product->publisher ?? 'N/A' }}</td>
                                    <td>{{ $item->product->title ?? 'N/A' }}</td>
                                    <td>{{ $item->product->mrp ?? '0' }}</td>
                                    <td>{{ $item->product->selling_price ?? '0' }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->requestedBy->name ?? 'System' }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    @can('Product Listing -> Modified Listing Status ->  Delete Button')
                                    <td>
                                        <form method="POST" action="{{ route('modify-listing.delete', $item->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        No data found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2 : Request Completed --}}
                <div class="tab-pane" id="tab2">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>PRODUCT ID</th>
                                    <th>IMAGE</th>
                                    <th>Request Type</th>
                                    <th>PUBLISHER</th>
                                    <th>BOOK NAME</th>
                                    <th>MRP.</th>
                                    <th>SELLING</th>
                                    <th>STATUS</th>
                                    <th>Requested By</th>
                                    <th>Updated By</th>
                                    <th>Updated Time</th>
                                    @can('Product Listing -> Modified Listing Status ->  Delete Button')
                                    <th>ACTION</th>
                                    @endcan

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completed as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product_id }}</td>
                                    <td>
                                        <a href="{{ $item->product->base_url ?? '#' }}" target="_blank">
                                            <img src="{{ $item->product->base_url ?? asset('assets/images/no-image.png') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </a>
                                    </td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->product->publisher ?? 'N/A' }}</td>
                                    <td>{{ $item->product->title ?? 'N/A' }}</td>
                                    <td>{{ $item->product->mrp ?? '0' }}</td>
                                    <td>{{ $item->product->selling_price ?? '0' }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->requestedBy->name ?? 'System' }}</td>
                                    <td>{{ $item->updatedBy->name ?? 'N/A' }}</td>
                                    <td>{{ $item->updated_at->format('d-m-Y H:i') }}</td>
                                    @can('Product Listing -> Modified Listing Status ->  Delete Button')
                                    <td>
                                        <form method="POST" action="{{ route('modify-listing.delete', $item->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted">
                                        No data found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
