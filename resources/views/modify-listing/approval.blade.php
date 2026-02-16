@extends('layouts.master')

@section('title', __('Review Modify Listings (DB)'))

@push('css')
<style>
    .card,
    .card-body,
    .table-responsive {
        background: #fff !important;
    }
    
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
@endpush

@section('content')
<div class="main-container container-fluid">
    <!--<div class="page-header">-->
        
    <!--</div>-->

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                {{-- Tabs Header --}}
                <div class="card-header">
                    <h1 class="page-title">{{ __('Review Modify Listings (DB)') }}</h1>
                </div>

                <div class="card-body">
                    <div class="tabs-menu1">
                        <ul class="nav panel-tabs">
                            <li>
                                <a href="#tab1" class="active" data-bs-toggle="tab">
                                    Exchange with other Similar Books ({{ $exchange->count() }})
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-bs-toggle="tab">
                                    Update To Latest Edition ({{ $update->count() }})
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tab-content">

                        {{-- TAB 1 : Exchange with Others --}}
                        <div class="tab-pane active" id="tab1">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>PRODUCT ID</th>
                                            <th>PUBLISHER</th>
                                            <th>BOOK NAME</th>
                                            <th>MRP.</th>
                                            <th>SELLING</th>
                                            <th>STATUS</th>
                                            <th>Requested By</th>
                                            <th>Requested Time</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($exchange as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->product_id }}</td>
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
                                            <td>
                                                <a href="{{ route('listing.edit.database', $item->product_id) }}?modify_id={{ $item->id }}"
                                                   class="btn btn-sm btn-primary">
                                                    Edit (DB)
                                                </a>
                                            </td>
                                            <!--<td>-->
                                            <!--    <form method="POST" action="{{ route('modify-listing.delete', $item->id) }}"-->
                                            <!--          style="display:inline;">-->
                                            <!--        @csrf-->
                                            <!--        @method('DELETE')-->
                                            <!--        <button type="submit"-->
                                            <!--                class="btn btn-danger btn-sm"-->
                                            <!--                onclick="return confirm('Are you sure?')">-->
                                            <!--            Delete-->
                                            <!--        </button>-->
                                            <!--    </form>-->
                                            <!--</td>-->
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
                                                No data found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TAB 2 : Update To Latest --}}
                        <div class="tab-pane" id="tab2">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>PRODUCT ID</th>
                                            <th>PUBLISHER</th>
                                            <th>BOOK NAME</th>
                                            <th>MRP.</th>
                                            <th>SELLING</th>
                                            <th>STATUS</th>
                                            <th>Requested By</th>
                                            <th>Requested Time</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($update as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->product_id }}</td>
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
                                            <td>
                                                <a href="{{ route('listing.edit.database', $item->product_id) }}?modify_id={{ $item->id }}"
                                                   class="btn btn-sm btn-primary">
                                                    Edit (DB)
                                                </a>
                                            </td>
                                            <!--<td>-->
                                            <!--    <form method="POST" action="{{ route('modify-listing.delete', $item->id) }}"-->
                                            <!--          style="display:inline;">-->
                                            <!--        @csrf-->
                                            <!--        @method('DELETE')-->
                                            <!--        <button type="submit"-->
                                            <!--                class="btn btn-danger btn-sm"-->
                                            <!--                onclick="return confirm('Are you sure?')">-->
                                            <!--            Delete-->
                                            <!--        </button>-->
                                            <!--    </form>-->
                                            <!--</td>-->
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">
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
    </div>
</div>
@endsection
