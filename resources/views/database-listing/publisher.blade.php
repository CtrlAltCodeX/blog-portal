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
                    <h3 class="card-title">Publishers</h3>
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
                                    <th>{{ __('Publisher Name') }}</th>
                                    <th>{{ __('No. of Listings Affected ') }}</th>
                                    <th>{{ __('View List') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($listings as $key => $googlePost)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $googlePost->publisher }}</td>
                                    <td>{{ $googlePost->publisherCount($googlePost->publisher) }}</td>
                                    <td>
                                        <a href='{{ route("listing.specific.publishers", $googlePost->publisher) }}?paging=25'>
                                            <i class='fa fa-eye'></i>
                                        </a>
                                    </td>
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