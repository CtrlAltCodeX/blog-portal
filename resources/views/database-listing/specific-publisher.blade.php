@extends('layouts.master')

@section('title', __('Publisher'))

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

    .tick {
        border: 1px solid green;
        background-color: green;
        color: white !important;
        padding: 5px;
    }
     
    .close {
        border: 1px solid red;
        background-color: red;
        color: white !important;
        padding: 5px;
    }

    .thumb {
        width: 60px;
        cursor: zoom-in;
    }

    .image-preview {
        position: fixed;
        display: none; /* hidden initially */
        top: 0;
        left: 0;
        width: 400px;
        border: 1px solid #ddd;
        background: #fff;
        z-index: 9999;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

</style>
@endpush

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Publisher</h3>

                    <div class="d-flex align-items-center justify-content-between" style="grid-gap: 10px;">

                        <strong>{{ __('Total Count:') }}</strong><span>{{ $listings->total() }}</span>

                        <form action="" method="get" id='pagingform'>
                            <select class="form-control w-100" id='paging' name="paging">
                                <option value="25" {{ request()->paging == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request()->paging == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request()->paging == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Product ID') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    {{-- <th>{{ __('Action') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($listings as $key => $googlePost)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        <a href="{{ $googlePost->url }}" target="_blank">
                                            {{ $googlePost->product_id }}
                                        </a>
                                    </td>
                                    <td>
                                        <img 
                                            class="thumb" 
                                            src="{{ $googlePost->base_url ? $googlePost->base_url : '' }}" 
                                            data-full="{{ $googlePost->base_url ? $googlePost->base_url : '/dummy.jpg' }}"
                                            onerror="this.onerror=null;this.src='/dummy.jpg';"
                                            alt="Product Image">
                                    </td>
                                    <td>{{ $googlePost->title }}</td>
                                    <td>{{ '₹'.$googlePost->selling_price }}</td>
                                    <td>{{ '₹'.$googlePost->mrp }}</td>
                                    @php
                                        $categories = collect($googlePost->categories??[])->toArray();
                                    @endphp
                                    <td>
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ implode(", ", $categories) }}">
                                            {{ count($categories ?? []) }}
                                        </span>
                                    </td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->created_at)) }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->updated_at)) }}</td>
                                    {{-- <td>
                                        <div class='d-flex'>
                                            @can('Inventory -> Manage Inventory -> Edit')
                                            <a href="{{ route('listing.edit.database', $googlePost->product_id) }}?price_issue=true" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('Inventory -> Manage Inventory -> Delete')
                                            <form action="{{ route('listing.destroy', $googlePost->product_id) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td> --}}
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>

                        {!! $listings->appends(request()->all())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imagePreview" class="image-preview">
  <img id="imagePreviewImg" src="" alt="Preview">
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

@include('database-listing.listting-script')


@endpush