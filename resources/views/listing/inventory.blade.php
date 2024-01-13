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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Inventory</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
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
                                            $doc->loadHTML($googlePost->content);
                                            $selling = $doc->getElementById('selling')->textContent;
                                            $mrp = $doc->getElementById('mrp')->textContent;
                                            $image = $doc->getElementsByTagName("img")->item(0)->getAttribute('src');
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
                                            <td>@if(isset($image)) <img src="{{ $image }}" alt="Product Image"/> @else "No Image" @endif</td>
                                            <td>{{ $googlePost->id.'/'.$googlePost->title }}</td>
                                            <td>₹{{ $mrp }}</td>
                                            <td>₹{{ $selling }}</td>
                                            <td>{{ date("d-m-Y", strtotime($googlePost->published)).'/'.date("d-m-Y", strtotime($googlePost->updated)) }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    @can('Inventory edit')
                                                        <a href="{{ route('listing.edit', $googlePost->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                                    @endcan
                                                    @can('Inventory delete')
                                                        <form action="{{ route('listing.destroy', $googlePost->id) }}" method="POST" class="ml-2">
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
                </div>
            </div>
        </div>
    </div>
@endcan
@endsection

@push('js')
<script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="../assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="../assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="../assets/js/table-data.js"></script>
@endpush