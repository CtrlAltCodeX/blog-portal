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
                                @forelse ($googlePosts as $googlePost)
                                @php
                                $doc = new \DOMDocument();

                                @endphp
                                <tr>
                                    <td>
                                        @if(isset($googlePost->labels) && in_array('Stk_o', $googlePost->labels))
                                        {{ 'Out of Stock' }}
                                        @elseif(isset($googlePost->labels) && in_array('Stk_d', $googlePost->labels))
                                        {{ 'On Demand' }}
                                        @elseif(isset($googlePost->labels) && in_array('Stk_b', $googlePost->labels))
                                        {{ 'Pre Booking' }}
                                        @elseif(isset($googlePost->labels) && in_array('Stk_l', $googlePost->labels))
                                        {{ 'Low Stock' }}
                                        @else {{ 'In Stock' }}
                                        @endif
                                    </td>
                                    <td>{{ $googlePost->images??'No Image' }}</td>
                                    <td>{{ $googlePost->id.'/'.$googlePost->title }}</td>
                                    <td>{{ 'MRP' }}</td>
                                    <td>{{ 'Selling' }}</td>
                                    <td>{{ date("d-m-Y", strtotime($googlePost->published)).'/'.date("d-m-Y", strtotime($googlePost->updated)) }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('listing.edit', $googlePost->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            <form action="{{ route('listing.destroy', $googlePost->id) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('No records found.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $googlePosts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection