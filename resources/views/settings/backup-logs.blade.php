@extends('layouts.master')

@section('title', __('Backup Logs'))

@section('content')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center" style="grid-gap:15px;">
                            <input type="text" disabled class="form-control" value="{{ url('/') }}/storage/merchant-file.xlsx" />
                            <img src="/copy.png" width="25" title="Copy URL" class="copy" id="{{ url('/') }}/storage/merchant-file.xlsx" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'google']) }}" class="btn btn-primary w-25 mt-2">Merchant File</a>
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'google']) }}" class="btn btn-primary w-25 mt-2">Export File</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center" style="grid-gap:15px;">
                            <input type="text" disabled class="form-control" value="{{ url('/') }}/storage/facebook-file.xlsx" />
                            <img src="/copy.png" width="25" title="Copy URL" class="copy" id="{{ url('/') }}/storage/facebook-file.xlsx" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'facebook']) }}" class="btn btn-primary w-25 mt-2">Facebook File</a>
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'facebook']) }}" class="btn btn-primary w-25 mt-2">SQL File</a>
                        </div>

                        <!-- <a href="{{ route('backup.export', ['file' => 1, 'type' => 'facebook']) }}" class="mr-2 btn btn-primary">Export File (DB)</a> -->
                        <!-- <a href="{{ route('manually.backup') }}" class="mr-2 btn btn-primary">Manual Backup</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th>{{ __('Sl') }}</th>
                            <th>{{ __('Batch id') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Started') }}</th>
                            <th>{{ __('Completed') }}</th>
                            <th>{{ __('Export File') }}</th>
                            <th>{{ __('Merchant File') }}</th>
                            <th>{{ __('Facebook File') }}</th>
                            <th>{{ __('Email to') }}</th>
                            <th>{{ __('Download') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $key => $log)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{ $log->batch_id }}</td>
                            <td>{{ date("d-m-Y", strtotime($log->started)) }}</td>
                            <td>{{ date("d-m-Y h:i A", strtotime($log->started)) }}</td>
                            <td>{{ date("d-m-Y h:i A", strtotime($log->completed)) }}</td>
                            <td>{{ date("d-m-Y h:i A", strtotime($log->export_file)) }}</td>
                            <td>{{ date("d-m-Y h:i A", strtotime($log->merchant_file)) }}</td>
                            <td>{{ date("d-m-Y h:i A", strtotime($log->facebook_file)) }}</td>
                            <td>
                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ $log->email_to }}">
                                    View
                                    </button>
                            </td>
                            <td>
                                <a target="_blank" href='{{url("/")}}/storage/merchant-file{{$log->merchant_file}}.tsv' class="btn btn-sm btn-primary">Merchant</a>
                                <a target="_blank" href='{{url("/")}}/storage/facebook-file{{$log->facebook_file}}.xlsx' class="btn btn-sm btn-primary">Facebook</a>
                                <a target="_blank" href='{{url("/")}}/storage/report-file{{$log->export_file}}.xlsx' class="btn btn-sm btn-primary">Export</a>
                                <a target="_blank" href='{{url("/")}}' class="btn btn-sm btn-primary">SQL</a>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {!! $logs->links() !!}
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
        $(".copy").click(function() {
            navigator.clipboard.writeText($(this).attr('id'));
            alert('Copied');
        });

        $('#basic-datatable').DataTable({
            "paging": false
        });
    })
</script>
@endpush