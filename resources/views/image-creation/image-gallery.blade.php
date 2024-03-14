@extends('layouts.master')

@section('title', __('Image Gallery'))

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
</style>
@endpush

@section('content')

<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Image Gallery</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($files as $key => $file)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="/storage/uploads/{{ $file['name'] }}" alt="Product Image" width="100" /></td>
                                    <td>{{ $file['name'] }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($file['datetime'])) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ url('/') }}/storage/uploads/{{ $file['name'] }}" id='downloadMultipleImage' download="product-image.jpg">
                                                <button class="btn btn-sm btn-primary">Download</button>
                                                <!-- <img src="/downlod-icon.png" class="m-1" /> -->
                                            </a>
                                            <a href='#' class="copy" id="{{ url('/') }}/storage/uploads/{{ $file['name'] }}"">
                                            <button class=" btn btn-sm btn-primary">Copy</button>
                                                <!-- <img src=" /copy.png" width="25" class="m-1" /> -->
                                            </a>
                                            <a href="/storage/uploads/{{ $file['name'] }}" target="_blank" id='downloadMultipleImage'>
                                                <button class="btn btn-sm btn-primary">View</button>
                                                <!-- <img src="/eye.png" width="25" class="m-1" /> -->
                                            </a>
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

        $("#category").on("change", function() {
            $("#form").submit();
        })
    })
</script>
@include('image-creation.script')

@endpush