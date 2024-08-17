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
                        <h5>Google Merchant Center</h5>
                        <div class="d-flex align-items-center" style="grid-gap:15px;">
                            <input type="text" disabled class="form-control" value="{{ url('/') }}/storage/merchant-file.tsv" />
                            <img src="/copy.png" width="25" title="Copy URL" class="copy" id="{{ url('/') }}/storage/merchant-file.tsv" />
                        </div>
                        <h5 class="mb-0 mt-2">Social Media Files</h5>
                        <div class="d-flex" style="grid-gap: 10px;">
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'google']) }}" class="btn btn-primary mt-2">Download Merchant File</a>
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'facebook']) }}" class="btn btn-primary mt-2">Download Facebook File</a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5>Facebook Commerce Manager</h5>
                        <div class="d-flex align-items-center" style="grid-gap:15px;">
                            <input type="text" disabled class="form-control" value="{{ url('/') }}/storage/facebook-file.csv" />
                            <img src="/copy.png" width="25" title="Copy URL" class="copy" id="{{ url('/') }}/storage/facebook-file.csv" />
                        </div>
                        <h5 class="mb-0 mt-2">Export DB Files</h5>
                        <div class="d-flex" style="grid-gap: 10px;">
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'report']) }}" class="btn btn-primary mt-2">Download DB File ( XSL )</a>
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'sql']) }}" class="btn btn-primary mt-2">Download DB File ( SQL )</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h3 class="card-title">Backup Logs History</h3>
                <button class="btn btn-primary" id='backup'>Manual Backup</button>
            </div>
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th>{{ __('Sl') }}</th>
                            <th>{{ __('Batch id') }}</th>
                            <th>{{ __('Backup Date') }}</th>
                            <th>{{ __('Started') }}</th>
                            <th>{{ __('Completed') }}</th>
                            <th>{{ __('Export File') }}</th>
                            <th>{{ __('Merchant File') }}</th>
                            <th>{{ __('Facebook File') }}</th>
                            <th>{{ __('SQL File') }}</th>
                            <th>{{ __('Email to') }}</th>
                            <th>{{ __('Download') }}</th>
                            <th>{{ __('Error') }}</th>
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
                            <td>{{ date("d-m-Y h:i A", strtotime($log->sql_file)) }}</td>
                            <td>
                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ $log->email_to }}">
                                    View
                                </span>
                            </td>
                            <td>
                                <div class="d-flex" style="grid-gap: 10px;">
                                    <a target="_blank" href='{{url("/")}}/storage/merchant-file{{strtotime($log->merchant_file)}}.tsv'>
                                        <i class="fa fa-google" style="font-size:24px"></i>
                                        <!-- <img src="/google.png" width="25" /> -->
                                    </a>
                                    <a target="_blank" href='{{url("/")}}/storage/facebook-file{{strtotime($log->facebook_file)}}.csv'>
                                        <i class="fa fa-facebook" style="font-size:24px"></i>
                                        <!-- <img src="/facebook.png" width="25" /> -->
                                    </a>
                                    <a target="_blank" href='{{url("/")}}/storage/report-{{strtotime($log->export_file)}}.xlsx'>
                                        <i class="fa fa-file-excel-o" style="font-size:24px"></i>
                                        <!-- <img src="/excel.png" width="25" /> -->
                                    </a>
                                    <a target="_blank" href='{{url("/")}}/storage/export-file{{strtotime($log->export_file)}}.sql'>
                                        <i class="fa fa-database" style="font-size:24px"></i>
                                        <!-- <img src="/sql.png" width="25" /> -->
                                    </a>
                                </div>
                            </td>
                            <td>
                                @if($log->error)
                                <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ $log->error }}">
                                    View
                                </span>
                                @endif
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

        $('#backup').click(function() {
            $.ajax({
                type: "POST",
                url: "{{ route('backup.run.backup') }}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    localStorage.setItem('backup', result);
                    location.reload();
                },
            });
        })

        $.ajax({
            type: "GET",
            url: "{{ route('get.queues') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: localStorage.getItem('backup'),
            },
            success: function(result) {
                if (result) {
                    $("#backup").html('Backing Up');
                    $("#backup").attr('disabled', true);
                } else {
                    $("#backup").html('Manual Backup');
                }
            },
        });
    })
</script>
@endpush