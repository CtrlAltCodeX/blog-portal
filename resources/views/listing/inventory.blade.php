@extends('layouts.master')

@section('title', __('Manage Inventory'))

@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        Manage Inventory
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap text-md-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product ID/Product name') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Selling Price') }}</th>
                                    <th>{{ __('Created/Updated date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Status</td>
                                    <td>image</td>
                                    <td>Product Nme</td>
                                    <td>mrp</td>
                                    <td>price</td>
                                    <td>date</td>
                                    <td>buttons</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection