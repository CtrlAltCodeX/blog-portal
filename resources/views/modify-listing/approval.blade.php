@extends('layouts.master')

@section('title', __('Approval Section - Modify Listing'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('Approval Section') }}</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="panel panel-primary w-100">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu">
                                <ul class="nav panel-tabs">
                                    <li><a href="#tab1" class="active" data-bs-toggle="tab">Exchange with Others</a></li>
                                    <li><a href="#tab2" data-bs-toggle="tab">Update To Latest</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
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
                                        @foreach($exchange as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->product_id }}</td>
                                            <td>{{ $item->product->publisher ?? 'N/A' }}</td>
                                            <td>{{ $item->product->title ?? 'N/A' }}</td>
                                            <td>{{ $item->product->mrp ?? '0' }}</td>
                                            <td>{{ $item->product->selling_price ?? '0' }}</td>
                                            <td><span class="badge bg-warning text-dark">{{ $item->status }}</span></td>
                                            <td>{{ $item->requestedBy->name ?? 'System' }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('listing.edit.database', $item->product_id) }}?modify_id={{ $item->id }}" class="btn btn-sm btn-primary">Edit (DB)</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                                        @foreach($update as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->product_id }}</td>
                                            <td>{{ $item->product->publisher ?? 'N/A' }}</td>
                                            <td>{{ $item->product->title ?? 'N/A' }}</td>
                                            <td>{{ $item->product->mrp ?? '0' }}</td>
                                            <td>{{ $item->product->selling_price ?? '0' }}</td>
                                            <td><span class="badge bg-warning text-dark">{{ $item->status }}</span></td>
                                            <td>{{ $item->requestedBy->name ?? 'System' }}</td>
                                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('listing.edit.database', $item->product_id) }}?modify_id={{ $item->id }}" class="btn btn-sm btn-primary">Edit (DB)</a>
                                            </td>
                                        </tr>
                                        @endforeach
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
